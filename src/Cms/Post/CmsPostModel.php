<?php

namespace Pars\Admin\Cms\Post;

use Laminas\Db\Sql\Where;
use Pars\Bean\Finder\BeanFinderInterface;
use Pars\Bean\Type\Base\BeanInterface;
use Pars\Admin\Article\ArticleModel;
use Pars\Core\Database\DatabaseBeanConverter;
use Pars\Helper\Parameter\FilterParameter;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Cms\Post\CmsPostBeanFinder;
use Pars\Model\Cms\Post\CmsPostBeanProcessor;
use Pars\Model\Cms\Post\State\CmsPostStateBeanFinder;
use Pars\Model\Cms\Post\Type\CmsPostTypeBeanFinder;

/**
 * Class CmsPostModel
 * @package Pars\Admin\Cms\Post
 */
class CmsPostModel extends ArticleModel
{
    public function initialize()
    {
        $this->setBeanFinder(new CmsPostBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new CmsPostBeanProcessor($this->getDbAdpater()));
        $this->getBeanFinder()->filterLocale_Code($this->getTranslator()->getLocale());
    }



    public function getCmsPostType_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new CmsPostTypeBeanFinder($this->getDbAdpater());
        $finder->setCmsPostType_Active(true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('CmsPostType_Code')] = $this->translate('cmsposttype.code.' . $bean->get('CmsPostType_Code'));
        }
        return $options;
    }

    public function getCmsPostState_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
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
        if (isset($data['CmsPage_ID'])) {
            $finder = new CmsPostBeanFinder($this->getDatabaseAdapter());
            $finder->filterLocale_Code($this->getUserBean()->getLocale()->getLocale_Code());
            $count = $finder->count();
            $bean->set('Article_Code', 'blogpost-' . $count);
            $bean->set('ArticleTranslation_Code', 'blogpost-' . $count);
            $bean->set('ArticleTranslation_Name', $this->translate('cmspost.name.default') . ' ' . $count);
        }
        return $bean;
    }

    public function handleFilter(FilterParameter $filterParameter)
    {
        parent::handleFilter($filterParameter);
        if ($filterParameter->hasAttribute('CmsPost_Published')
        && $filterParameter->getAttribute('CmsPost_Published') == 'true') {
            $where = new Where();
            $where->greaterThan('CmsPost_PublishTimestamp', (new \DateTime())->format(DatabaseBeanConverter::DATE_FORMAT), Where::TYPE_IDENTIFIER, Where::TYPE_VALUE);
            $this->getBeanFinder()->getBeanLoader()->filterValue($where);
            $this->getBeanFinder()->filter(['CmsPostState_Code' => 'inactive'], BeanFinderInterface::FILTER_MODE_OR);
        }
    }


}
