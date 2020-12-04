<?php

namespace Pars\Admin\Translation;



use Pars\Admin\Base\BaseDelete;
use Pars\Admin\Base\BaseDetail;
use Pars\Admin\Base\BaseEdit;
use Pars\Admin\Base\BaseOverview;
use Pars\Admin\Base\CrudController;
use Pars\Admin\Base\SystemNavigation;
use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Span;


/**
 * Class TranslationController
 * @package Pars\Admin\Translation
 * @method TranslationModel getModel()
 */
class TranslationController extends CrudController
{
    protected function initModel()
    {
        parent::initModel();
        $this->setPermissions('translation.create', 'translation.edit', 'translation.delete');
    }


    public function isAuthorized(): bool
    {
        return $this->checkPermission('translation');
    }

    protected function initView()
    {
        parent::initView();
        $this->getView()->getLayout()->getNavigation()->setActive('system');
        $subNavigation = new SystemNavigation($this->getPathHelper(), $this->getTranslator(), $this->getUserBean());
        $subNavigation->setActive('translation');
        $this->getView()->getLayout()->setSubNavigation($subNavigation);
    }

    protected function createOverview(): BaseOverview
    {
        return new TranslationOverview($this->getPathHelper(),$this->getTranslator(), $this->getUserBean());
    }

    protected function createDetail(): BaseDetail
    {
        return new TranslationDetail($this->getPathHelper(),$this->getTranslator(), $this->getUserBean());
    }

    public function editAction()
    {
        parent::editAction();
        $this->clearcacheAction(false);
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
    }


    protected function createEdit(): BaseEdit
    {
        $edit = new TranslationEdit($this->getPathHelper(),$this->getTranslator(), $this->getUserBean());
        $edit->setLocaleOptions($this->getModel()->getLocale_Options());
        return $edit;
    }

    protected function createDelete(): BaseDelete
    {
        return new TranslationDelete($this->getPathHelper(),$this->getTranslator(), $this->getUserBean());
    }


}
