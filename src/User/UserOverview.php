<?php

namespace Pars\Admin\User;

use Pars\Bean\Type\Base\BeanListAwareTrait;
use Pars\Admin\Base\BaseOverview;
use Pars\Component\Base\Field\Badge;

class UserOverview extends BaseOverview
{
    use BeanListAwareTrait;

    protected function initName()
    {
        $this->setName($this->translate('section.user'));
    }


    protected function initialize()
    {

        $badge = new Badge('{UserState_Code}');
        $badge->setFormat(new UserStateFieldFormat($this->getTranslator()));
        $this->pushField($badge);
        $this->addField('User_Username', $this->translate('user.username'));
        $this->addField('Person_Firstname', $this->translate('person.firstname'));
        $this->addField('Person_Lastname', $this->translate('person.lastname'));
        parent::initialize();
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
