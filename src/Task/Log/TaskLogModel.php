<?php


namespace Pars\Admin\Task\Log;


use Pars\Admin\Base\CrudModel;
use Pars\Model\Task\Log\TaskLogBeanFinder;
use Pars\Model\Task\Log\TaskLogBeanProcessor;

class TaskLogModel extends CrudModel
{
    public function initialize()
    {
        parent::initialize();
        $this->setBeanFinder(new TaskLogBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new TaskLogBeanProcessor($this->getParsContainer()));
    }

}
