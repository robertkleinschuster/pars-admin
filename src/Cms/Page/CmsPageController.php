<?php

namespace Pars\Admin\Cms\Page;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Article\ArticleController;
use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\ContentNavigation;
use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Toolbar\DownloadButton;
use Pars\Component\Base\Toolbar\ToolbarButton;
use Pars\Component\Base\Toolbar\UploadButton;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Mvc\Controller\ControllerResponse;


/**
 * Class CmsPageController
 * @package Pars\Admin\Cms\Page
 * @method CmsPageModel getModel()
 */
class CmsPageController extends ArticleController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('cmspage.create', 'cmspage.edit', 'cmspage.delete');
    }

    public function isAuthorized(): bool
    {
        return $this->checkPermission('cmspage');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('content');
        $subNavigation = new ContentNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('cmspage');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    protected function createOverview(): BaseOverview
    {
        return new CmsPageOverview($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    protected function createDetail(): BaseDetail
    {
        $this->getView()->set('CmsPage_ID', (int)$this->getControllerRequest()->getId()->getAttribute('CmsPage_ID'));
        $detail = new CmsPageDetail($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        return $detail;
    }

    protected function createEdit(): BaseEdit
    {
        $edit = new CmsPageEdit($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->setTypeOptions($this->getModel()->getCmsPageType_Options());
        $edit->setStateOptions($this->getModel()->getCmsPageState_Options());
        $edit->setRedirectOptions($this->getModel()->getCmsPageRedirect_Options());
        $edit->setFileOptions($this->getModel()->getFileOptions());
        $edit->setFileBeanList($this->getModel()->getFileBeanList());
        $edit->setLayoutOptions($this->getModel()->getCmsPageLayout_Options());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        return new CmsPageDelete($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
    }

    public function detailAction()
    {
        $detail = parent::detailAction();
        $this->pushAction('cmspost', 'index', $this->translate('section.post'), self::SUB_ACTION_MODE_TABBED);
        $this->pushAction('cmspageparagraph', 'index', $this->translate('section.paragraph'), self::SUB_ACTION_MODE_TABBED);
        switch ($detail->getBean()->get('CmsPageType_Code')) {
            case 'contact':
                $this->pushAction('articledata', 'index', $this->translate('section.data.contact'), self::SUB_ACTION_MODE_TABBED);
                break;
            case 'poll':
                $this->pushAction('articledata', 'index', $this->translate('section.data.poll'), self::SUB_ACTION_MODE_TABBED);
                break;
        }
        $this->loadRedirectInfo($detail->getBean());
        $detail->getSubToolbar()->push((new DownloadButton())->addOption('noajax')->setPath($this->getPathHelper(true)->setAction('export')->getPath()));
        return $detail;
    }

    public function indexAction()
    {
        $overview = parent::indexAction();
        $overview->getToolbar()->push((new UploadButton())->setModal(true)->setPath($this->getPathHelper(true)->setAction('import')->getPath()));
        return $overview;
    }


    protected function loadRedirectInfo(BeanInterface $bean)
    {

        if (!$bean->empty('CmsPage_ID_Redirect')) {
            $cmsPageFinder = new CmsPageBeanFinder($this->getModel()->getDbAdpater());
            $cmsPageFinder->setLocale_Code($this->getTranslator()->getLocale());
            $cmsPageFinder->setCmsPage_ID($bean->get('CmsPage_ID_Redirect'));
            $page = $cmsPageFinder->getBean();
            $alert = new Alert($this->translate('cmspage.redirect.alert.headline'));
            $idParameter = new IdParameter();
            $idParameter->addId('CmsPage_ID', $page->get('CmsPage_ID'));
            $path = $this->getPathHelper(true)->setId($idParameter);
            $alert->addParagraph($page->get('ArticleTranslation_Name'))->setPath($path);
            $this->getView()->prepend($alert);
        }
    }

    /**
     * @throws \Niceshops\Core\Exception\AttributeExistsException
     * @throws \Niceshops\Core\Exception\AttributeLockException
     * @throws \Pars\Mvc\Exception\NotFoundException
     */
    public function exportAction()
    {
        $this->getControllerResponse()->setDownload(
            'page_' . $this->getModel()->getBean()->get('Article_Code') . '.pars',
            json_encode($this->getModel()->export()),
            'application/json'
        );
    }


    public function importAction()
    {
        $edit = new CmsPageImport($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $edit->setBean($this->getModel()->getEmptyBean($this->getPreviousAttributes()));
        $this->getModel()->getBeanConverter()
            ->convert($edit->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $edit->setToken($this->generateToken('submit_token'));
        $this->getView()->append($edit);
        return $edit;
    }
}
