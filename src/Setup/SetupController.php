<?php

namespace Pars\Admin\Setup;

use Pars\Admin\Base\BackofficeBeanConverter;
use Pars\Admin\Base\BaseController;
use Pars\Model\Authentication\User\UserBean;
use Pars\Model\Database\DatabaseMiddleware;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Edit\Fields\Text;

/**
 * Class SetupController
 * @package Pars\Admin\Setup
 */
class SetupController extends BaseController
{
    protected function initView()
    {
        parent::initView();
        $this->getView()->setLayout('layout/default');
    }

    public function init()
    {
        $this->initView();
        $this->initModel();
    }


    protected function initModel()
    {
        $this->getModel()->setBeanConverter(new BackofficeBeanConverter());

        $this->getModel()
            ->setDbAdapter($this->getControllerRequest()->getServerRequest()->getAttribute(DatabaseMiddleware::ADAPTER_ATTRIBUTE));
        $this->getModel()->initialize();
        $metadata = \Laminas\Db\Metadata\Source\Factory::createSourceFromAdapter($this->getModel()->getDbAdpater());
        $tableNames = $metadata->getTableNames($this->getModel()->getDbAdpater()->getCurrentSchema());
        if (in_array('Person', $tableNames) && in_array('User', $tableNames)) {
            $count = $this->getModel()->getBeanFinder()->count();
        } else {
            $count = 0;
        }
        if ($count > 0) {
            $this->getControllerResponse()->setRedirect($this->getRedirectPath()->getPath());
        } else {
            $this->getModel()->addOption(SetupModel::OPTION_CREATE_ALLOWED);
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
        $this->getView()->setHeading($this->translate('setup.title'));
        $this->getView()->setHeading($this->translate('create.title'));
        $edit = new Edit();
        $this->addEditFields($edit);
        $edit->addSubmitCreate(
            $this->translate('create.submit'),
            (new RedirectParameter())->setLink($this->getRedirectPath()->getPath())
        );
        $edit->addCancel($this->getRedirectPath()->getPath(), $this->translate('create.cancel'), true);
        $edit->getValidationHelper()->addErrorFieldMap($this->getValidationErrorMap());
        $this->getView()->append($edit);
        $bean = $this->getModel()->getEmptyBean();
        $edit->setBean($bean);
        $bean->setData('User_Password', '');
        foreach ($edit->getFieldList() as $item) {
            $bean->setData($item->getKey(), $this->getControllerRequest()->getAttribute($item->getKey(), true));
        }
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->setCols(2);
        $edit->addText('Person_Firstname', $this->translate('person.firstname'))
            ->setChapter($this->translate('user.edit.personaldata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_GIVEN_NAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('Person_Lastname', $this->translate('person.lastname'))
            ->setChapter($this->translate('user.edit.personaldata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_FAMILY_NAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('User_Displayname', $this->translate('user.displayname'))
            ->setChapter($this->translate('user.edit.personaldata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_NICKNAME)
            ->setAppendToColumnPrevious(true);
        $edit->addText('User_Username', $this->translate('user.username'))
            ->setChapter($this->translate('user.edit.signindata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_USERNAME);
        $edit->addText('User_Password', $this->translate('user.password'))
            ->setType(Text::TYPE_PASSWORD)
            ->setChapter($this->translate('user.edit.signindata'))
            ->setAutocomplete(Text::AUTOCOMPLETE_NEW_PASSWORD)
            ->setAppendToColumnPrevious(true);
        $edit->addSubmitAttribute('UserState_Code', UserBean::STATE_ACTIVE)
            ->setAppendToColumnPrevious(true);
    }
}
