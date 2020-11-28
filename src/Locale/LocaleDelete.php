<?php


namespace Pars\Admin\Locale;


use Pars\Admin\Base\BaseDelete;

class LocaleDelete extends BaseDelete
{
    protected function initialize()
    {
        parent::initialize();
    }
    protected function getRedirectController(): string
    {
        return 'locale';
    }




}
