<?php


namespace Pars\Admin\Article\Data;


use Pars\Admin\Base\BaseDelete;

class ArticleDataDelete extends BaseDelete
{
    protected function getRedirectController(): string
    {
        return 'articledata';
    }

    protected function getRedirectAction(): string
    {
        return 'index';
    }

    protected function getRedirectIdFields(): array
    {
        return [];
    }


}
