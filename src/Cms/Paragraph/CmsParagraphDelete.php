<?php


namespace Pars\Admin\Cms\Paragraph;


use Pars\Admin\Article\ArticleDelete;

class CmsParagraphDelete extends ArticleDelete
{
    protected function getRedirectController(): string
    {
        return 'cmsparagraph';
    }

}
