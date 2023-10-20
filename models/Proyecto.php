<?php

namespace Model;

class Proyecto extends ActiveRecord {
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url','propietarioId'];

    public function __construct($args = [])
    {
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
    }

    public $id;
    public $proyecto;
    public $url;
    public $propietarioId;
    

    public function validarProyecto() :array {
        if(!$this->proyecto) {
            self::$alertas['error'][] = 'El nombre del Proyecto es obligatorio';
        }
        if (strlen($this->proyecto)>60){
            self::$alertas['error'][] = 'El nombre del Proyecto ha de ser inferior a 60 caracteres';
        }
        return self::$alertas;
    }
}