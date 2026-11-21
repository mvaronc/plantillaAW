<?php

namespace PlantillaAW\Model;

use PlantillaAW\Database\Database;

class Model {
    protected $table = null;
    protected $fillable = [];// Campos que se pueden rellenar en la tabla, para evitar inyecciones de SQL.
    protected $restrictions = [];//nombre del campo y la expresión regular que debe cumplir, para validar los datos antes de insertarlos en la base de datos.

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
    public function getFillable() {
        return $this->fillable;
    }
    public function setFillable($fillable) {
        $this->fillable = $fillable;
    }
    public function getRestrictions() {
        return $this->restrictions;
    }
    public function setRestrictions($restrictions) {
        $this->restrictions = $restrictions;
    }
    public function getTable() {
        return $this->table;
    }
    public function getAsArray() {
        $datos = [];
        foreach ($this->fillable as $campo) {
            $datos[$campo] = $this->{$campo} ?? null;
        }
        return $datos;
    }
}
