<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {
    public static function index () :void {
        session_start();
        isAuth();

        $proyectoUrl = $_GET['id'];

        if (!$proyectoUrl){header('location: /dashboard');}
        $proyecto = Proyecto::where('url', $proyectoUrl);
        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']){header('location: /404');}

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);
        echo json_encode(['tareas' => $tareas]);
        return;

    }
    
    public static function crear () :void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            isAuth();

            $proyecto = Proyecto::where('url', s($_POST['proyectoUrl']));
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'No se ha podido crear la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 

            // Crear e instanciar tarea
            $tarea = new Tarea(sArray($_POST));
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();

            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea Creada correctamente',
                    'id' => $resultado['id'],
                    'proyectoId' => $proyecto->id
                ];
            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Error, consulte con el administrador'
                ];
            }
            echo json_encode($respuesta);

            return;
        }
    }

    public static function actualizar () :void {
        session_start();
        isAuth();


        if ($_SERVER['REQUEST_METHOD'] === 'POST'){

            $proyecto = Proyecto::where('url', s($_POST['url']));
            $id = s($_POST['id']);

            if($proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'No se ha podido actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = Tarea::find($id);
            $tarea->sincronizar(sArray($_POST));
            $resultado = $tarea->guardar();
            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea Actualizada correctamente',
                    'id' => $tarea->id,
                    'proyectoId' => $proyecto->id
                ];
            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Error, consulte con el administrador'
                ];
            }
            echo json_encode($respuesta);
            return;
        }
    }
    
    public static function eliminar () :void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
            $proyectoId = Proyecto::where('url', s($_POST['url']))->id;

            // Comprobacion que el proyecto acedido desde el API es el mismo que la ID que la API envia del front
            if($proyectoId === s($_POST['proyectoId'])) {
                // Acceso a la tarea
                $tarea = Tarea::find(s($_POST['id']));
                // Si existe
                if($tarea) {
                    // Se elimna y se da respuesta afirmativa
                    $respuesta = $tarea->eliminar();
                    if($respuesta) {
                        echo json_encode([
                            'respuesta' => $respuesta
                        ]);
                        return;
                    }
                }
                // si no hay tarea se da respuesta negativa
                echo json_encode([
                    'resultado' => 'error'
                ]);
                return;

            }

        }
    }
}