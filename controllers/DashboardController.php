<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController
{
    // Muestra proyectos
    public static function index(Router $router)
    {
        session_start();
        isAuth();

        $id = $_SESSION['id'];
        $proyectos = Proyecto::belongsTo('propietarioId', $id);


        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }
    // Crea proyectos
    public static function crearProyecto(Router $router)
    {
        session_start();
        isAuth();

        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto(sArray($_POST));

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if (empty($alertas)) {
                // Generar url unica
                $url = md5(uniqid());
                $proyecto->url = $url;
                // Asignar creador proyecto
                $proyecto->propietarioId = $_SESSION['id'];
                // Guardar el proyecto
                $proyecto->guardar();
                // Redireccionar
                header('location: /proyecto?id=' . $proyecto->url);
            }
        }

        $router->render('dashboard/crear-proyecto', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }
    // Edicion del perfil, no password
    public static function perfil(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];

        // Busqueda del usuario
        $usuario = Usuario::find($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Sincronizado
            $usuario->sincronizar(sArray($_POST));
            // Validado
            $alertas = $usuario->validarPerfil();

            $emailExistente = Usuario::where('email', $usuario->email);

            if ($emailExistente && $emailExistente->id !== $_SESSION['id']) {
                $usuario::setAlerta('error', 'Email no valido');
                $alertas = Usuario::getAlertas();
            }

            if (empty($alertas)) {
                // Guardado
                $usuario->guardar();
                // Actualizacion de la sesion
                $_SESSION['nombre'] = $usuario->nombre;
                $_SESSION['email'] = $usuario->email;

                Usuario::setAlerta('exito', 'Pefil actualizado correctamente');
                $alertas = Usuario::getAlertas();
            }
        }

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'alertas' => $alertas,
            'usuario' => $usuario
        ]);
    }
    // Muestra el proyecto, las tareas se obtienen por API desde el controlador TareaController
    public static function proyecto(Router $router)
    {
        session_start();
        isAuth();

        // Obtener y validar el token
        $token = $_GET['id'];
        if (!$token) {
            header('location: /dashboard');
        }
        $proyecto = Proyecto::where('url', $token);

        // Revisar que el visitante sea el propietario del proyecto
        if ($proyecto->propietarioId !== $_SESSION['id']) {
            header('location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }
    // Formulario para el cambio de password
    public static function cambiarPassword(Router $router)
    {
        session_start();
        isAuth();
        $alertas = [];

        // TODO MOVER VERIFICACION AL MODELO
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $usuario = Usuario::find($_SESSION['id']);
            $usuario->sincronizar(sArray($_POST));
            $alertas = $usuario->validarPasswordCambio();
            if (empty($alertas)) {
                $alertas = $usuario->validarNuevoPassword();
                if (empty($alertas)) {
                    $usuario->password = $usuario->password_nuevo;
                    
                    unset($usuario->password_nuevo);
                    unset($usuario->password_actual);
                    unset($usuario->password2);
                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();

                    if ($resultado) {
                        Usuario::setAlerta('exito', 'Password Actualizada Correctamente');
                    }
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'cambiar Password',
            'alertas' => $alertas
        ]);
    }
}


//localhost:3000/proyecto?id=4d6ed558e8a7243a0ae51936036fe525