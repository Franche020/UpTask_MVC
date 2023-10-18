<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {
    public static function index () {

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
                    'mensaje' => 'Tarea Creada correctamente'
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
        if ($_SERVER['REQIEST_METHOD'] === 'POST'){
            
        }
    }
    
    public static function eliminar () {
        if ($_SERVER['REQIEST_METHOD'] === 'POST'){
            
        }
    }
}