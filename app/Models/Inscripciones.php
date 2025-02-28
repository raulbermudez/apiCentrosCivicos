<?php
namespace App\Models;

class Inscripciones extends DBAbstractModel{
    private static $instancia;
    private $id;
    private $correo;

    // Setters
    public function setId($id){
        $this->id = $id;
    }
    public function setCorreo($correo){
        $this->correo = $correo;
    }

    // Modelo singleton
    public static function getInstancia()
    {
        if (!isset(self::$instancia)) {
            $miClase = __CLASS__;
            self::$instancia = new $miClase;
        }
        return self::$instancia;
    }

    // Metodo para ñadir una inscripcion
    public function set($data = array()){
        $this->query = "INSERT INTO inscripciones (id_actividad, nombre, telefono, correo, fecha_inscripcion, estado) 
        VALUES (:id_actividad, :nombre, :telefono, :correo, :fecha_inscripcion, :estado)";
        $this->parametros['id_actividad'] = $data['id_actividad'];
        $this->parametros['nombre'] = $data['nombre'];
        $this->parametros['telefono'] = $data['telefono'];
        $this->parametros['correo'] = $this->correo;
        $this->parametros['fecha_inscripcion'] = $data['fecha_inscripcion'];
        $this->parametros['estado'] = $data['estado'];
        $this->get_results_from_query();
    }

    // Metodo para obtener todas las inscripciones
    public function get(){
        $this->query = "SELECT * FROM inscripciones WHERE id = :id";
        $this->parametros['id'] = $this->id;
        $this->get_results_from_query();
        return $this->rows;
    }
    public function edit($id = '', $data = array()){

    }

    // Metodo para eliminar una inscripcion
    public function delete($id= ''){
        $this->query = "DELETE FROM inscripciones WHERE id = :id";
        $this->parametros['id'] = $id;
        $this->get_results_from_query();
    }

    // Metodo para obtener todas las inscripciones de un usuario a traves de su email
    public function getAllByUserEmail($email){
        $this->query = "SELECT * FROM inscripciones WHERE correo = :correo";
        $this->parametros['correo'] = $email;
        $this->get_results_from_query();
        return $this->rows;
    }
}