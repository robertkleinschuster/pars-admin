<?php

namespace Pars\Admin\Setup;

use Laminas\I18n\Translator\TranslatorAwareInterface;
use Pars\Admin\Authentication\SigninLayout;
use Pars\Admin\Base\BaseController;
use Pars\Component\Base\View\BaseView;
use Pars\Core\Database\DatabaseMiddleware;
use Pars\Core\Database\ParsDatabaseAdapter;
use Pars\Core\Translation\ParsTranslatorAwareInterface;
use Pars\Helper\Path\PathHelper;
use Pars\Model\Authentication\User\UserBean;
use Pars\Mvc\View\ViewBeanConverter;

/**
 * Class SetupController
 * @package Pars\Admin\Setup
 */
class SetupController extends BaseController
{
    protected function initView()
    {
        $this->setView(new BaseView());
        $layout = new SigninLayout();
        $this->getView()->setLayout($layout);
        $this->injectStaticFiles();
    }

    public function init()
    {
        $this->initView();
        $this->initModel();
    }


    protected function initModel()
    {
        $this->getModel()->setBeanConverter(new ViewBeanConverter());
        $this->getModel()->initialize();
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->getModel()->getDatabaseAdapter());
        $tableNames = $metadata->getTableNames($this->getModel()->getDatabaseAdapter()->getCurrentSchema());
        if (in_array('Person', $tableNames) && in_array('User', $tableNames)) {
            $count = $this->getModel()->getBeanFinder()->count();
        } else {
            $count = 0;
        }
        if ($count > 0) {
            $this->getControllerResponse()->setRedirect($this->getRedirectPath()->getPath());
            header('Location: ' . $this->getRedirectPath()->getPath());
            exit;
        } else {
            $this->getModel()->addOption(SetupModel::OPTION_CREATE_ALLOWED);
        }
        if ($this->getModel()->hasBeanProcessor()) {
            $processor = $this->getModel()->getBeanProcessor();
            if ($processor instanceof ParsTranslatorAwareInterface) {
                $processor->setTranslator($this->getTranslator());
            }
        }
    }

    /**
     * @return PathHelper
     */
    protected function getRedirectPath(): PathHelper
    {
        return $this->getPathHelper()->setController('index')->setAction('index');
    }

    public function indexAction()
    {
        $setup = new Setup($this->getTranslator(), new UserBean());
        $setup->setCreate(true);
        $this->getView()->pushComponent($setup);
        $setup->setBean($this->getModel()->getEmptyBean());
        $setup->getBean()->set('Locale_Code', 'de_AT');
        $setup->getBean()->set('UserState_Code', 'active');
        $setup->setToken($this->getTokenName(), $this->generateToken($this->getTokenName()));
        $this->getModel()->getBeanConverter()
            ->convert($setup->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $setup->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
    }
}
