<?php

namespace PlantillaAW\Controller;

class Controller {
    protected $model;
    protected $view;

    public function __construct($model=null, $view=null) {
        $this->model = $model;
        $this->view = $view;
    }

    public function index() {
        $resultado = $this->model->getAll();
        return $this->view->index($resultado);
    }

    public function show($id) {
        $resultado = $this->model->getById($id);
        return $this->view->show($resultado);
    }

    public function store($datos) {
        $resultado = $this->model->insert($datos);
        return $this->view->show($resultado);
    }

    public function update($id, $datos) {
        $resultado = $this->model->updateById($id, $datos);
        return $this->view->show($resultado);
    }

    public function destroy($id) {
        $resultado = $this->model->deleteById($id);
        return $this->view->index($this->model->getAll());
    }

    public function create() {
        return $this->view->create();
    }

    public function edit($id) {
        $resultado = $this->model->getById($id);
        return $this->view->edit($resultado);
    }
    public function redirect($url) {
        header("Location: $url");
        exit();
    }
}
