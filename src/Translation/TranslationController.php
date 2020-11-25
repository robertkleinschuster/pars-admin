<?php

namespace Pars\Admin\Translation;



use Pars\Admin\Base\CrudController;
use Pars\Mvc\Helper\PathHelper;
use Pars\Helper\Parameter\IdParameter;
use Pars\Mvc\View\Components\Detail\Detail;
use Pars\Mvc\View\Components\Edit\Edit;
use Pars\Mvc\View\Components\Overview\Overview;

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

    protected function getDetailPath(): PathHelper
    {
        return $this->getPathHelper()->setId((new IdParameter())->addId('Translation_ID'));
    }

    protected function addOverviewFields(Overview $overview): void
    {
        $overview->addText('Locale_Code', $this->translate('translation.locale'));
        $overview->addText('Translation_Code', $this->translate('translation.code'));
        $overview->addText('Translation_Text', $this->translate('translation.text'));
        $overview->addText('Translation_Namespace', $this->translate('translation.namespace'));
    }

    protected function addDetailFields(Detail $detail): void
    {
        $detail->addText('Locale_Code', $this->translate('translation.locale'));
        $detail->addText('Translation_Code', $this->translate('translation.code'));
        $detail->addText('Translation_Text', $this->translate('translation.text'));
        $detail->addText('Translation_Namespace', $this->translate('translation.namespace'));
    }

    protected function addEditFields(Edit $edit): void
    {
        $edit->addSelect('Locale_Code', $this->translate('translation.locale'))
        ->setSelectOptions($this->getModel()->getLocale_Options())
        ->setValue($this->getTranslator()->getLocale());
        $edit->addText('Translation_Code', $this->translate('translation.code'));
        $edit->addSelect('Translation_Namespace', $this->translate('translation.namespace'))
        ->setSelectOptions([
            'frontend' => 'frontend',
            'backoffice' => 'backoffice',
            'default' => 'default',
        ]);
        $edit->addTextarea('Translation_Text', $this->translate('translation.text'))
        ->setRows(5);
    }
}
