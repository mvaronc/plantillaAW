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

    public function store($campos, $valores) {
        $resultado = $this->model->insert($campos, $valores);
        return $this->view->show($resultado);
    }

    public function update($id, $campos, $valores) {
        $resultado = $this->model->updateById($id, $campos, $valores);
        return $this->view->show($resultado);
    }

    public function destroy($id) {
        $resultado = $this->model->deleteById($id);
        return $this->view->index($resultado);
    }

    public function create() {
        return $this->view->create();
    }

    public function edit($id) {
        $resultado = $this->model->getById($id);
        return $this->view->edit($resultado);
    }
}
