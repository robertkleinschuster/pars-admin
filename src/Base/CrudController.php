<?php

namespace Pars\Admin\Base;

abstract class CrudController extends BaseController
{
    abstract public function indexAction();
    abstract public function detailAction();
    abstract public function createAction();
    abstract public function editAction();
    abstract public function deleteAction();
}
