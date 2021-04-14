<?php


namespace Pars\Admin\Task\Log;


use Pars\Admin\Base\BaseOverview;

class TaskLogOverview extends BaseOverview
{
    protected function initName()
    {
        parent::initName();
        $this->setName($this->translate('section.tasklog'));
    }


    protected function initAdditionalBefore()
    {
        $this->setShowCreate(false);
        $this->setShowCreateNew(false);
        $this->setShowDeleteBulk(false);
        parent::initAdditionalBefore();
    }


    protected function initFields()
    {
        parent::initFields();

        $this->setShowEdit(false);
        $this->setShowDelete(false);
        $this->addField('TaskLog_Message', $this->translate('tasklog.message'));
    }


    protected function getController(): string
    {
        return 'tasklog';
    }

    protected function getDetailIdFields(): array
    {
        return ['TaskLog_ID'];
    }

}
