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
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;

    // Validacion Login
    public function validarLogin() {
        $emailValido = filter_var($this->email, FILTER_VALIDATE_EMAIL);
        
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email de usuario es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if (!$emailValido){
            self::$alertas['error'][] = 'Debes proporcionar una direccion de email válida';
        }

        return self::$alertas;
    }


    // Validacion para nuevas cuentas
    public function validarNuevaCuenta () {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre de usuario es obligatorio';
        }
        if(!$this->email) {
            self::$alertas['error'][] = 'El Email de usuario es obligatorio';
        }
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es obligatorio';
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
    public function hashPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function generarToken () {
        $this->token = uniqid();
    }
    
    public function validarEmail() {
        $emailValido = filter_var($this->email, FILTER_VALIDATE_EMAIL);
        if (!$this->email){
            self::$alertas['error'][] = 'El email es obligatorio';
        }
        if (!$emailValido){
            self::$alertas['error'][] = 'Debes proporcionar una direccion de email válida';
        }

        return self::$alertas;        
    }

    public function validarPassword() {
        if(!$this->password){
            self::$alertas['error'][] = 'El Password es obligatorio';
        }
        if(strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El Password ha de tener al menos 6 caracteres';
        }

        return self::$alertas;
    }


}