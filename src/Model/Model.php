<?php

namespace PlantillaAW\Model;

use PlantillaAW\Database\Database;

class Model {
    protected $table = null;

    public function __construct($table) {
        $this->table = $table;
    }

    public function getAll() {
        return Database::select($this->table, "*");
    }

    public function getById($id) {
        return Database::select($this->table, "*", ["id"], [$id]);
    }

    public function insert($campos, $valores) {
        $datos = array_combine($campos, $valores);
        return Database::insert($this->table, $datos);
    }

    public function updateById($id, $campos, $valores) {
        $datos = array_combine($campos, $valores);
        return Database::update($this->table, $datos, "id='$id'");
    }

    public function deleteById($id) {
        return Database::delete($this->table, $id);
    }
}
