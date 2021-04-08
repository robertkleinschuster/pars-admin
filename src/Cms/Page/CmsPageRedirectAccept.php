<?php

namespace Pars\Admin\Cms\Page;

use Pars\Bean\Type\Base\BeanInterface;
use Pars\Mvc\View\FieldAcceptInterface;
use Pars\Mvc\View\FieldInterface;

class CmsPageRedirectAccept implements FieldAcceptInterface
{
    public function __invoke(FieldInterface $field, ?BeanInterface $bean = null): bool
    {
        return !$bean->empty('CmsPage_ID_Redirect');
    }
}
