<?php

namespace Pars\Admin\Locale;

use Niceshops\Bean\Type\Base\BeanInterface;
use Pars\Admin\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

class LocaleController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('locale.create', 'locale.edit', 'locale.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('locale');
    }

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('Locale_Code'));
    }


    protected function addOverviewFields(Overview $overview): void
    {
        $redirectPath = $this->getPathHelper()->getPath();
        $overview->addMoveUpIcon($this->getDetailPath()->addParameter((new MoveParameter())->setUp('Locale_Order')->setSteps(-1))
            ->addParameter((new RedirectParameter())->setLink($redirectPath))->getPath())
            ->setShow(function (BeanInterface $bean) {
                return $bean->hasData('Locale_Order') && $bean->getData('Locale_Order') > 1;
            });
        $overview->addMoveDownIcon($this->getDetailPath()->addParameter((new MoveParameter())->setDown('Locale_Order')->setSteps(1))
            ->addParameter((new RedirectParameter())->setLink($redirectPath))->getPath())
            ->setShow(function (BeanInterface $bean) {
                return $bean->hasData('Locale_Order') && $bean->getData('Locale_Order') < $this->getModel()->getBeanFinder()->count();
            });

        $overview->addBadgeBoolean(
            'Locale_Active',
            $this->translate('locale.active'),
            $this->translate('locale.active.true'),
            $this->translate('locale.active.false')
        );
        $overview->addText('Locale_Code', $this->translate('locale.code'));
        $overview->addText('Locale_Name', $this->translate('locale.name'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addBadgeBoolean(
            'Locale_Active',
            $this->translate('locale.active'),
            $this->translate('locale.active.true'),
            $this->translate('locale.active.false')
        );
        $detail->addText('Locale_Code', $this->translate('locale.code'));
        $detail->addText('Locale_Name', $this->translate('locale.name'));
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->addText('Locale_Code', $this->translate('locale.code'));
        $edit->addText('Locale_Name', $this->translate('locale.name'));
        $edit->addCheckbox('Locale_Active', $this->translate('locale.active'));
    }
}
