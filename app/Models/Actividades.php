<?php
namespace App\Models;

class Actividades extends DBAbstractModel{
    private static $instancia;
    private $id_centro;

    // Setters
    public function setIdCentro($id_centro){
        $this->id_centro = $id_centro;
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

    public function set($data = array()){

    }

    public function get(){

    }

    public function edit($id = '', $data = array()){

    }

    public function delete($id= ''){

    }

    // Método que va a devolver todas las actividades que pertenencen a un centro específico
    public function getAllByCentroId(){
        $this->query = "SELECT * FROM actividades WHERE id_centro = :id_centro";
        $this->parametros['id_centro'] = $this->id_centro;
        $this->get_results_from_query();
        return $this->rows;
    }

    // Método que devuelve todas las actividades
    public function getAll(){
        $this->query = "SELECT * FROM actividades";
        $this->get_results_from_query();
        return $this->rows;
    }
}