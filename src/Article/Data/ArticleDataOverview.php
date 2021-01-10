<?php


namespace Pars\Admin\Article\Data;


use Pars\Admin\Base\BaseOverview;

class ArticleDataOverview extends BaseOverview
{
    protected function initialize()
    {
        $this->setShowCreate(false);
        $this->setShowCreateNew(false);
        $this->setShowDelete(true);
        $this->setShowEdit(false);
        $this->setShowDeleteBulk(true);
        $this->setShowEditBulk(false);
        $this->setShowMove(false);
        $this->setSection($this->translate('section.data'));
        $this->addField('ArticleData_Timestamp', $this->translate('articledata.timestamp'));
        if (isset($this->get('data')['fields'])) {
            foreach ($this->get('data')['fields'] as $field => $label) {
                $this->addField($field, $label);
            }
        }
        parent::initialize();
    }


    protected function getController(): string
    {
        return 'articledata';
    }

    protected function getRedirectAction(): string
    {
        return 'index';
    }

    protected function getDetailIdFields(): array
    {
        return [
            'ArticleData_ID'
        ];
    }

    protected function getRedirectIdFields(): array
    {
        return ['ArticleData_ID'];
    }

    protected function getCreateIdFields(): array
    {
        return ['ArticleData_ID'];
    }


}
