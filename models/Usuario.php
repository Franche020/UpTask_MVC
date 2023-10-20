<?php

namespace Model;

class Usuario extends ActiveRecord {
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token' , 'confirmado'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->password_actual = $args['password_actual'] ?? '';
        $this->password_nuevo = $args['password-nuevo'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    // Validacion Login
    public function validarLogin() :array {     
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email de usuario es obligatorio';
        } else {
            $emailValido = filter_var($this->email, FILTER_VALIDATE_EMAIL);
            if (!$emailValido){
                self::$alertas['error'][] = 'Debes proporcionar una direccion de email válida';
            }
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
       

        return self::$alertas;
    }
    // Validacion para nuevas cuentas
    public function validarNuevaCuenta () :array {
        if(!$this->nombre ) {
            self::$alertas['error'][] = 'El Nombre de usuario es obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email de usuario es obligatorio';
        } else {
            $emailValido = filter_var($this->email, FILTER_VALIDATE_EMAIL);
            if (!$this->email){
                self::$alertas['error'][] = 'El formato del email no es correcto';
            }
        }
        if (!$emailValido){
            self::$alertas['error'][] = 'Debes proporcionar una direccion de email válida';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password ha de tener al menos 6 caracteres';
        }
        if($this->password !== $this->password2) {
            self::$alertas['error'][] = 'Los Password han de ser iguales';
        }

        return self::$alertas;
    }
    // Hash el password
    public function hashPassword() :void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }
    // Generacion de token para registro y recuerdo email
    public function generarToken () :void {
        $this->token = uniqid();
    }
    // Validacion de mail para recuerdo mail
    public function validarEmail() :array {
        $emailValido = filter_var($this->email, FILTER_VALIDATE_EMAIL);
        if (!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if (!$emailValido){
            self::$alertas['error'][] = 'Debes proporcionar una direccion de email válida';
        }

        return self::$alertas;        
    }
    // Validacion del password para login
    public function validarPassword() :array {
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password ha de tener al menos 6 caracteres';
        }

        return self::$alertas;
    }
    // Validacion del nuevo password
    public function validarNuevoPassword() :array {

        if(!$this->password){
            self::$alertas['error'][] = 'El Antiguo Password es obligatorio';
        } else {
            
            if(!$this->password_nuevo){
                self::$alertas['error'][] = 'El Nuevo Password es obligatorio';
            }
            if(strlen($this->password_nuevo) < 6) {
                self::$alertas['error'][] = 'El Password ha de tener al menos 6 caracteres';
            }
            if($this->password_nuevo !== $this->password2) {
                self::$alertas['error'][] = 'Los Password han de ser iguales';
            }
        }
        return self::$alertas;
    }
    // Valida que la contraseña sea correcta para elcambio
    public function validarPasswordCambio() :array {

        if(!password_verify($this->password_actual, $this->password)){   
            self::$alertas['error'][] = 'El password es incorrecto';
        } 
        
        return self::$alertas;
    }
    // Valida que la actualizacion de perfil sea correcta
    public function validarPerfil() :array {
        if(!$this->nombre ) {
            self::$alertas['error'][] = 'El Nombre de usuario es obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email de usuario es obligatorio';
        } else {
            $emailValido = filter_var($this->email, FILTER_VALIDATE_EMAIL);
            if (!$emailValido){
                self::$alertas['error'][] = 'Debes proporcionar una direccion de email válida';
            }
        }
        
        return self::$alertas;
    }


}