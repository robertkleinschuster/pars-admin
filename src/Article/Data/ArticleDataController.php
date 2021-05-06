<?php

namespace Pars\Admin\Article\Data;

use Pars\Bean\Type\Base\BeanException;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Admin\Base\CrudController;
use Pars\Mvc\Exception\NotFoundException;

/**
 * Class ArticleDataController
 * @package Pars\Admin\Article\Data
 */
class ArticleDataController extends CrudController
{

    /**
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('article.create', 'article.edit', 'article.delete');
    }

    /**
     * @return mixed|void
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getTranslator(), $this->getUserBean());
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        if (
            $this->getControllerRequest()->hasId()
            && $this->getControllerRequest()->getId()->hasAttribute('CmsPage_ID')
        ) {
            $this->getView()->set(
                'CmsPage_ID',
                (int) $this->getControllerRequest()->getId()->getAttribute('CmsPage_ID')
            );
            $subNavigation->setActive('cmspage');
        }
        if (
            $this->getControllerRequest()->hasId()
            && $this->getControllerRequest()->getId()->hasAttribute('CmsBlock_ID')
        ) {
            $this->getView()->set(
                'CmsBlock_ID',
                (int) $this->getControllerRequest()->getId()->getAttribute('CmsBlock_ID')
            );
            $subNavigation->setActive('cmsblock');
        }
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('article');
    }

    /**
     * @return BaseOverview
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws BeanException
     * @throws NotFoundException
     */
    public function indexAction()
    {
        $overview = parent::indexAction();
        if ($this->hasParent() && $this->getParent()->hasView()) {
            $parentBean = $this->getParent()->getModel()->getBean();
            $this->getModel()->getBeanFinder()->filter(['Article_ID' => $parentBean->get('Article_ID')]);
            $overview->set('parentBean', $parentBean);
        }
        return $overview;
    }
}
