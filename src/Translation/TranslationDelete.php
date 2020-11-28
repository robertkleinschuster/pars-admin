<?php


namespace Pars\Admin\Translation;


use Pars\Admin\Base\BaseDelete;

class TranslationDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'translation';
    }

}
