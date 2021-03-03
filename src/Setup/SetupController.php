<?php

namespace Pars\Admin\Setup;

use Laminas\I18n\Translator\TranslatorAwareInterface;
use Pars\Admin\Authentication\SigninLayout;
use Pars\Admin\Base\BaseController;
use Pars\Component\Base\View\BaseView;
use Pars\Core\Database\DatabaseMiddleware;
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
    }

    public function init()
    {
        $this->initView();
        $this->initModel();
    }


    protected function initModel()
    {
        $this->getModel()->setBeanConverter(new ViewBeanConverter());
        $this->getModel()
            ->setDbAdapter($this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE));
        $this->getModel()->initialize();
        $this->getModel()->setTranslator($this->getTranslator());
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->getModel()->getDbAdpater());
        $tableNames = $metadata->getTableNames($this->getModel()->getDbAdpater()->getCurrentSchema());
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
            if ($processor instanceof TranslatorAwareInterface) {
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
        $setup = new Setup($this->getPathHelper(), $this->getTranslator(), new UserBean());
        $setup->setCreate(true);
        $this->getView()->append($setup);
        $setup->setBean($this->getModel()->getEmptyBean());
        $setup->getBean()->set('Locale_Code', 'de_AT');
        $setup->getBean()->set('UserState_Code', 'active');
        $setup->setToken($this->generateToken('submit_token'));
        $this->getModel()->getBeanConverter()
            ->convert($setup->getBean(), $this->getPreviousAttributes())->fromArray($this->getPreviousAttributes());
        $setup->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
    }
}
