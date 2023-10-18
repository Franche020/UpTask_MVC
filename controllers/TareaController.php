<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {
    public static function index () {
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
    
    public static function crear () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            session_start();
            isAuth();

            $proyecto = Proyecto::where('url', $_POST['proyectoUrl']);
            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'No se ha podido crear la tarea'
                ];
                echo json_encode($respuesta);
                return;
            } 

            // Crear e instanciar tarea
            $tarea = new Tarea($_POST);
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

    public static function actualizar () {
        session_start();
        isAuth();


        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = Proyecto::where('url', $_POST['url']);
            $id = $_POST['id'];

            if($proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'No se ha podido actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = Tarea::find($id);
            $tarea->sincronizar($_POST);
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
    
    public static function eliminar () {
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            
        }
    }
}