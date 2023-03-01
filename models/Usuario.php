<?php

namespace Model;

class Usuario extends ActiveRecord {
    public static $tabla = 'usuario';
    public static $columnasDB = ['id', 'nombre', 'email', 'password',
    'cedula','tipo','token'];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $cedula;
    public $tipo;
    public $token;

    public function __construct($args=[]){
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->cedula = $args['cedula'] ?? '';
        $this->tipo = $args['tipo'] ?? '';
        $this->token = $args['token'] ?? NULL;
    }
    public function hashearPassword(){
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }
}
?>