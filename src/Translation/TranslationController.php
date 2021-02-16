<?php

namespace Pars\Admin\Translation;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Span;
use Pars\Mvc\Exception\NotFoundException;

/**
 * Class TranslationController
 * @package Pars\Admin\Translation
 * @method TranslationModel getModel()
 */
class TranslationController extends CrudController
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
        $this->setPermissions('translation.create', 'translation.edit', 'translation.delete');
    }

    /**
     * @return bool
     */
    public function isAuthorized(): bool
    {
        return $this->checkPermission('translation');
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
        $subNavigation->setActive('translation');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }


    /**
     * @return BaseEdit
     * @throws BeanException
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws NotFoundException
     */
    public function editAction()
    {
        $edit = parent::editAction();
        $edit->setLocaleOptions($this->getModel()->getLocale_Options());
        $source = $this->getModel()->getTranslationSource();
        if ($source) {
            $detail = $this->createDetail();
            $detail->setBean($source);
            $this->getView()->append($detail);
            $detail = new Detail();
            $detail->setSection($this->translate('translation.edit.placeholder'));
            foreach ($this->getView() as $key => $value) {
                $span = new Span($value, "[{$key}]");
                $detail->append($span);
            }
            $this->getView()->append($detail);
        }
        return $edit;
    }
}
