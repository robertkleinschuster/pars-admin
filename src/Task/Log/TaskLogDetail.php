<?php


namespace Pars\Admin\Task\Log;


use Pars\Admin\Base\BaseDetail;

class TaskLogDetail extends BaseDetail
{
    protected function initName()
    {
        parent::initName();
        $this->setName('{TaskLog_Message}');
    }

    protected function initAdditionalBefore()
    {
        $this->setShowEdit(false);
        $this->setShowDelete(false);
        parent::initAdditionalBefore();
    }


    protected function initFields()
    {
        parent::initFields();

        $this->addField('TaskLog_Text', $this->translate('tasklog.text'));
    }


    protected function getIndexController(): string
    {
        return 'tasklog';
    }

    protected function getEditIdFields(): array
    {
        return [];
    }

}
