<?php


namespace Pars\Admin\Article\Data;


use Pars\Admin\Base\BaseEdit;

class ArticleDataEdit extends BaseEdit
{
    protected function getRedirectController(): string
    {
        return 'article';
    }

    protected function getRedirectAction(): string
    {
        return parent::getRedirectAction();
    }

    protected function getCreateRedirectIdFields(): array
    {
        return ['Article_ID'];
    }

    protected function getCreateRedirectAction(): string
    {
        return parent::getCreateRedirectAction();
    }

    protected function getRedirectIdFields(): array
    {
        return ['Article_ID'];
    }

}
