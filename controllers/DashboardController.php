<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController {

    public static function index(Router $router) {
        session_start();

        isAuth();

        $router->render('dashboard/index',[
            'titulo' => 'Proyectos'
        ]);
    }
    // Crea proyectos
    public static function crearProyecto(Router $router){
        session_start();

        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $proyecto = new Proyecto($_POST);

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)){
                // Generar url unica
                $url = md5(uniqid());
                $proyecto->url = $url;
                // Asignar creador proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                // Guardar el proyecto
                $proyecto->guardar();
                // Redireccionar
                header('location: /proyecto?id='.$proyecto->url);
            }
        }
        
        $router->render('dashboard/crear-proyecto' ,[
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router){
        session_start();

        isAuth();
        
        $router->render('dashboard/perfil' ,[
            'titulo' => 'Perfil'
        ]);
    }

    public static function proyecto () {
        
    }

}