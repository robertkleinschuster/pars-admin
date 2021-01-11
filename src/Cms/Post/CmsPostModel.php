<?php

namespace Pars\Admin\Cms\Post;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudModel;
use Pars\Model\Cms\Post\CmsPostBeanFinder;
use Pars\Model\Cms\Post\CmsPostBeanProcessor;
use Pars\Model\Cms\Post\State\CmsPostStateBeanFinder;
use Pars\Model\Cms\Post\Type\CmsPostTypeBeanFinder;

class CmsPostModel extends CrudModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPostBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPostBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->setLocale_Code($this->getTranslator()->getLocale());
    }

    public function getCmsPostType_Options(): array
    {
        $options = [];
        $finder = new CmsPostTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPostType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPostType_Code')] = $bean->get('CmsPostType_Code');
        }
        return $options;
    }

    public function getCmsPostState_Options(): array
    {
        $options = [];
        $finder = new CmsPostStateBeanFinder($this->getDbAdpater());
        $finder->setCmsPostState_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPostState_Code')] = $bean->get('CmsPostState_Code');
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
