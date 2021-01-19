<?php


namespace Pars\Admin\Article\Data;


use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Cms\Page\CmsPagePollDetail;
use Pars\Model\Cms\Page\CmsPageBean;

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
        if ($this->exists('parent')) {
            $parent = $this->get('parent');
            if ($parent instanceof CmsPageBean) {
                switch ($parent->CmsPageType_Code) {
                    case 'contact':
                        $this->contact();
                        break;
                    case 'poll':
                        $this->poll();
                        break;
                }
            }
        }
        parent::initialize();
    }


    protected function poll()
    {
        $this->setSection($this->translate('section.data.poll'));
        $this->addField('ArticleData_Data[name]', $this->translate('articledata.data.name'));
        $this->addField('ArticleData_Data[option]', $this->translate('articledata.data.option'));
        $data = [];
        foreach ($this->getBeanList() as $bean) {
            if (isset($bean->ArticleData_Data['option'])) {
                if (isset($data[$bean->ArticleData_Data['option']])) {
                    $data[$bean->ArticleData_Data['option']]++;
                } else {
                    $data[$bean->ArticleData_Data['option']] = 1;
                }
            }
        }
        $pollDetal = new CmsPagePollDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $pollDetal->setResultData($data);
        $this->getBefore()->push($pollDetal);
    }


    public function contact()
    {
        $this->setSection($this->translate('section.data.contact'));
        $this->addField('ArticleData_Data[name]', $this->translate('articledata.data.name'));
        $this->addField('ArticleData_Data[email]', $this->translate('articledata.data.email'));
    }

    protected function getController(): string
    {
        return 'articledata';
    }

    protected function getRedirectController(): string
    {
        if ($this->exists('parent')) {
            $parent = $this->get('parent');
            if ($parent instanceof CmsPageBean) {
                return 'cmspage';
            }
        }
        return $this->getController();
    }


    protected function getRedirectAction(): string
    {
        if ($this->exists('parent')) {
            $parent = $this->get('parent');
            if ($parent instanceof CmsPageBean) {
                return 'detail';
            }
        }
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
        if ($this->exists('parent')) {
            $parent = $this->get('parent');
            if ($parent instanceof CmsPageBean) {
                return ['CmsPage_ID'];
            }
        }
        return [];
    }

    protected function getCreateIdFields(): array
    {
        return [];
    }


}
