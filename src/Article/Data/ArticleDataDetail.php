<?php


namespace Pars\Admin\Article\Data;


use Pars\Admin\Base\BaseDetail;

class ArticleDataDetail extends BaseDetail
{
    protected function initialize()
    {
        $this->setShowEdit(false);
        $data = $this->getBean()->get('ArticleData_Data');
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $this->addField("ArticleData_Data[$key]", $this->translate('articledata.data.' . $key));
            }
        }
        parent::initialize();
    }


    protected function getIndexController(): string
    {
        return 'articledata';
    }

    protected function getIndexAction(): string
    {
        return 'index';
    }

    protected function getIndexIdFields(): array
    {
        return [];
    }

    protected function getEditIdFields(): array
    {
        return ['ArticleData_ID'];
    }

}
