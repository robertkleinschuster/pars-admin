<?php

namespace Pars\Admin\Article\Data;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
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
        $subNavigation = new ContentNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmspage');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
        if (
            $this->getControllerRequest()->hasId()
            && $this->getControllerRequest()->getId()->hasAttribute('CmsPage_ID')
        ) {
            $this->getView()->set(
                'CmsPage_ID',
                (int) $this->getControllerRequest()->getId()->getAttribute('CmsPage_ID')
            );
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
            $overview->set('parent', $parentBean);
        }
        return $overview;
    }
}
