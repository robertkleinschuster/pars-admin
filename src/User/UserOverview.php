<?php

namespace Pars\Admin\User;

use Pars\Bean\Type\Base\BeanListAwareTrait;
use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;

class UserOverview extends BaseOverview
{
    use BeanListAwareTrait;

    protected function initName()
    {
        $this->setName($this->translate('section.user'));
    }


    protected function initialize()
    {


        parent::initialize();
    }

    protected function initBase()
    {
        parent::initBase();
        $this->setShowOrder(true);
    }


    protected function initFields()
    {
        parent::initFields();
        $badge = new Span('{UserState_Code}');
        $badge->setFormat(new UserStateFieldFormat($this->getTranslator()));
        $this->pushField($badge);
        $this->addFieldOrderable('User_Username', $this->translate('user.username'));
        $this->addFieldOrderable('Person_Firstname', $this->translate('person.firstname'));
        $this->addFieldOrderable('Person_Lastname', $this->translate('person.lastname'));
    }




    protected function getController(): string
    {
        return 'user';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'Person_ID'
        ];
    }

    protected function getCreateIdFields(): array
    {
        return [];
    }
}
