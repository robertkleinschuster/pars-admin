<?php

namespace Pars\Admin\Config;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Component\Base\Edit\Edit;
use Pars\Component\Base\Field\Headline;
use Pars\Helper\Parameter\FilterParameter;

/**
 * Class ConfigController
 * @package Pars\Admin\Config
 * @method ConfigModel getModel()
 */
class ConfigController extends CrudController
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
        $this->setPermissions('config.create', 'config.edit', 'config.delete');
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
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        if ($this->getControllerRequest()->hasId() && $this->getControllerRequest()->getId()->hasAttribute('ConfigType_Code')) {
            $subNavigation->setActive( $this->getControllerRequest()->getId()->getAttribute('ConfigType_Code'));
        } else {
            $subNavigation->setActive('config');
        }
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('user');
    }

    public function indexAction()
    {
        $this->addFilter_Select(
            'ConfigType_Code',
            $this->translate('configtype.code'),
            $this->getModel()->getConfigType_Options(true)
        );
        return parent::indexAction();
    }



}
