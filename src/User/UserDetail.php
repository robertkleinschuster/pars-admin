<?php

declare(strict_types=1);

namespace Pars\Admin\User;

use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Badge;
use Pars\Component\Base\Field\Span;


/**
 * Class Detail
 * @package Pars\Component\Components\Detail
 */
class UserDetail extends BaseDetail
{

    protected function initialize()
    {
        parent::initialize();
        $this->getJumbotron()->setHeadline('{Person_Firstname} {Person_Lastname}');
        $this->getJumbotron()->getFieldList()->push(new Span('{User_Username}', 'Benutzername'));
        $this->getJumbotron()->getFieldList()->push(new Span('{User_Displayname}', 'Anzeigename'));
        $state = new Badge('{UserState_Code}');
        $state->setLabel('Status');
        $state->setFormat(new UserStateFieldFormat($this->getTranslator()));
        $this->getJumbotron()->getFieldList()->push($state);
    }
}
