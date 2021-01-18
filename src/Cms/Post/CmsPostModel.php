<?php

namespace Pars\Admin\Cms\Post;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Article\ArticleModel;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\DataBean;
use Pars\Model\Cms\Post\CmsPostBeanFinder;
use Pars\Model\Cms\Post\CmsPostBeanProcessor;
use Pars\Model\Cms\Post\State\CmsPostStateBeanFinder;
use Pars\Model\Cms\Post\Type\CmsPostTypeBeanFinder;

class CmsPostModel extends ArticleModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPostBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPostBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    protected function create(IdParameter $idParameter, array &$attributes): void
    {

        $attributes['Article_Data']['__class'] = DataBean::class;
        $attributes['Article_Data']['User_Displayname_Create'] = $this->getUserBean()->User_Displayname;
        parent::create($idParameter, $attributes);
    }


    protected function save(array $attributes): void
    {
        $attributes['Article_Data']['__class'] = DataBean::class;
        $attributes['Article_Data']['User_Displayname_Edit'] = $this->getUserBean()->User_Displayname;
        parent::save($attributes);
    }


    public function getCmsPostType_Options(): array
    {
        $options = [];
        $finder = new CmsPostTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPostType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPostType_Code')] = $this->translate('cmsposttype.code.' . $bean->get('CmsPostType_Code'));
        }
        return $options;
    }

    public function getCmsPostState_Options(): array
    {
        $options = [];
        $finder = new CmsPostStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPostState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPostState_Code')] = $this->translate('cmspoststate.code.' . $bean->get('CmsPostState_Code'));
        }
        return $options;
    }

    public function getEmptyBean(array $data = []): BeanInterface
    {
        $bean = parent::getEmptyBean($data);
        $bean->set('CmsPost_PublishTimestamp', new \DateTime());
        return $bean;
    }


}
