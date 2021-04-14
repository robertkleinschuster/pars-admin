<?php


namespace Pars\Admin\Task\Log;

use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;

class TaskLogController extends CrudController
{
    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('tasklog');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('tasklog');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    public function indexAction()
    {
        return parent::indexAction();
    }


}
