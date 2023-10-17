<?php

namespace Controllers;

use Model\Proyecto;
use MVC\Router;

class DashboardController {

    public static function index(Router $router) {
        session_start();
        isAuth();
        
        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);


        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
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

    public static function proyecto (Router $router) {
        session_start();
        isAuth();

        // Obtener y validar el token
        $token = $_GET['id'];
        if (!$token) {header('location: /dashboard');}
        $proyecto = Proyecto::where('url', $token);

        // Revisar que el visitante sea el propietario del proyecto
        if($proyecto->propietarioId !== $_SESSION['id']){header('location: /dashboard');}

        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto
        ]);
    }

}


//localhost:3000/proyecto?id=4d6ed558e8a7243a0ae51936036fe525