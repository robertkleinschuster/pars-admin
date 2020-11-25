<?php

namespace Pars\Admin\User;

use Pars\Admin\Base\CrudController;
use Pars\Component\User\UserDelete;
use Pars\Component\User\UserDetail;
use Pars\Component\User\UserEdit;
use Pars\Component\User\UserOverview;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\ViewBeanConverter;


/**
 * Class UserController
 * @package Pars\Admin\Controller
 * @method UserModel getModel()
 */
class UserController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('user.create', 'user.edit', 'user.delete');
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('user');
    }

    public function indexAction()
    {
        $overview = new UserOverview();
        $overview->setDetailPath($this->getPathHelper()->setAction('detail')->setId((new IdParameter())->addId('Person_ID')));
        $overview->setEditPath($this->getPathHelper()->setAction('edit')->setId((new IdParameter())->addId('Person_ID')));
        $overview->setBeanList($this->getModel()->getBeanList());
        $this->getView()->append($overview);
    }

    public function detailAction()
    {
      $detail = new UserDetail();
      $detail->setBean($this->getModel()->getBean());
      $this->getView()->append($detail);
    }

    public function createAction()
    {
        $edit = new UserEdit();
        $edit->setBean($this->getModel()->getBean());
        $this->getView()->append($edit);
    }

    public function editAction()
    {
        $edit = new UserEdit();
        $edit->setBean($this->getModel()->getBean());
        $this->getView()->append($edit);
    }

    public function deleteAction()
    {
        $delete = new UserDelete();
        $this->getView()->append($delete);
    }


}
