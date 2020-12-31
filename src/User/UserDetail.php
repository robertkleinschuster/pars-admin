<?php

declare(strict_types=1);

namespace Pars\Admin\User;

use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Badge;


/**
 * Class Detail
 * @package Pars\Component\Components\Detail
 */
class UserDetail extends BaseDetail
{

    protected function initialize()
    {
        $this->setSection($this->translate('section.user'));


        parent::initialize();
        $this->setHeadline('{Person_Firstname} {Person_Lastname}');
        $this->addField('User_Username', $this->translate('user.username'));
        $this->addField('User_Displayname', $this->translate('user.displayname'));
        $this->addField('Locale_Name', $this->translate('user.locale'));
        $state = new Badge('{UserState_Code}');
        $state->setLabel('Status');
        $state->setFormat(new UserStateFieldFormat($this->getTranslator()));
        $this->append($state);
    }

    protected function getIndexController(): string
    {
        return 'user';
    }

    protected function getEditController(): string
    {
        return 'user';
    }


    protected function getIndexAction(): string
    {
        return 'index';
    }

    protected function getIndexIdFields(): array
    {
        return [];
    }

    protected function getEditAction(): string
    {
        return 'edit';
    }

    protected function getEditIdFields(): array
    {
        return ['Person_ID'];
    }


}
