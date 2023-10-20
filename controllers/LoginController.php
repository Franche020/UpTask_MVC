<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login (Router $router) {
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario(sArray($_POST));

            $alertas = $usuario->validarLogin();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || $usuario->confirmado === '0') {
                    Usuario::setAlerta('error', 'El usuario no existe o no está confirmado');
                } else {
                    // El usuario existe, comprobacion de password
                    if(password_verify($_POST['password'],$usuario->password)){
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // redireccionar
                        header('location: /dashboard');                        
                    } else {
                        Usuario::setAlerta('error', 'Password incorrecto');
                    }
                }
                
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesión',
            'alertas' => $alertas
        ]);
    }
    public static function logout (Router $router) {
        session_start();
        $_SESSION = [];
        header('location: /');

    }
    public static function crear (Router $router) {

        $usuario = new Usuario();
        $alertas = [];

        
        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar(sArray($_POST));
            $alertas = $usuario->validarNuevaCuenta();
            
            if(empty($alertas)){
                // Comprobacion que no existe el usuario
                $existeUsuario = Usuario::where('email', $usuario->email);
                if($existeUsuario) {
                    Usuario::setAlerta('error', 'El usuario ya está registrado');
                    $alertas = Usuario::getAlertas();
                } else {
                    // hashear password
                    $usuario->hashPassword();

                    // Eliminar password2 del objeto
                    unset($usuario->password2);

                    // Generar un token
                    $usuario->generarToken();

                    // Crear un nuevo usuario
                    $resultado = $usuario->guardar();

                    // Enviar el email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    
                    if($resultado) {
                        header('location: /mensaje');
                    }
                }


            }

            }

        $router->render('auth/crear', [
            'titulo' => 'Nueva Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function olvide (Router $router) {
        $alertas = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario = new Usuario(sArray($_POST));
            $alertas = $usuario->validarEmail();

            if (empty($alertas)){
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario  && $usuario->confirmado === '1'){

                    // Generar un nuevo token
                    $usuario->generarToken();
                    unset($usuario->password2);

                    // Actualizar el usuario
                    $usuario->guardar();

                    // Enviar el mail
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarRecuperacion();

                    // Generar la nueva alerta
                    Usuario::setAlerta('exito', 'Revisa tu email para recuperar tu contraseña');

                } else {
                    Usuario::setAlerta('error','El usuario no existe o no está confirmado');
                }
            }
        }
        $alertas = Usuario::getAlertas();

        $router->render('auth/olvide', [
            'titulo' => 'Recuperar Contraseña',
            'alertas' => $alertas
        ]);
    }
    public static function reestablecer (Router $router) {
        $token = s($_GET['token']);
        $mostrar = true;

        if(!$token) header('location: /');

        // identificar usuario con token
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no valido');
            $mostrar = false;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST'){
            $usuario->sincronizar(sArray($_POST));

            // validar el password
            $alertas = $usuario->validarPassword();

            // hash del password
            $usuario->hashPassword();
            // Quitar password2
            unset($usuario->password2);
            // Quitar el token
            $usuario->token = null;

            $resultado = $usuario->guardar();

            if($resultado) header('location: /');
        }
        
        $alertas = Usuario::getAlertas();

        $router->render('auth/reestablecer', [
            'titulo' => 'Reestablecer Password',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }
    public static function mensaje (Router $router) {
        

        $router->render('auth/mensaje', [
            'titulo' => 'Cuenta Creada Exitosamente'
        ]);
    }
    public static function confirmar (Router $router) {
        if(isset($_GET['token'])) {
            $token = s($_GET['token']);
        } else {
            header('location: /');
        }

        $usuario = Usuario::where('token', $token);

        if (empty($usuario)){
            Usuario::setAlerta('error', 'Token no válido');
        } else   {
            Usuario::setAlerta('exito', 'Cuenta confirmada correctamente, pulsa en iniciar sesión para continuar');
            // Confirmar la cuenta
            $usuario->confirmado = 1;
            $usuario->token = '';
            unset($usuario->password2);

            $usuario->guardar();
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirmar', [
            'titulo' => 'Confirma tu cuenta UpTask',
            'alertas' => $alertas
        ]);
    }
}