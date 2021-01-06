<?php

namespace Pars\Admin\Import;

use Pars\Admin\Base\CrudModel;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Import\Tesla\TeslaImporter;
use Pars\Model\Article\Translation\ArticleTranslationBeanFinder;
use Pars\Model\Import\ImportBeanFinder;
use Pars\Model\Import\ImportBeanProcessor;
use Pars\Model\Import\Type\ImportTypeBeanFinder;

/**
 * Class ImportModel
 * @package Pars\Admin\Import
 */
class ImportModel extends CrudModel
{
    public function initialize()
    {
        parent::initialize();
        $this->setBeanProcessor(new ImportBeanProcessor($this->getDbAdpater()));
        $this->setBeanFinder(new ImportBeanFinder($this->getDbAdpater()));
    }

    /**
     * @return array
     * @throws \Niceshops\Bean\Type\Base\BeanException
     */
    public function getImportTypeOptions(): array
    {
        $finder = new ImportTypeBeanFinder($this->getDbAdpater());
        $finder->setImportType_Active(true);
        $options = [];
        foreach ($finder->getBeanListDecorator() as $item) {
            $options[$item->get('ImportType_Code')] = $this->translate('importtype.code.' . $item->get('ImportType_Code'));
        }
        return $options;
    }

    public function getArticleOptions(): array
    {
        $finder = new ArticleTranslationBeanFinder($this->getDbAdpater());
        $finder->setLocale_Code($this->getTranslator()->getLocale());
        $options = [];
        foreach ($finder->getBeanListDecorator() as $item) {
            $options[$item->get('Article_ID')] = $item->get('ArticleTranslation_Name');
        }
        return $options;
    }

    public function handleSubmit(SubmitParameter $submitParameter, IdParameter $idParameter, IdListParameter $idListParameter, array $attribute_List)
    {
        parent::handleSubmit($submitParameter, $idParameter, $idListParameter, $attribute_List);
        switch ($submitParameter->getMode()) {
            case 'configure':
                if ($this->hasOption(self::OPTION_EDIT_ALLOWED)) {
                    $this->configure($attribute_List);
                } else {
                    $this->handlePermissionDenied();
                }
                break;
        }
    }


    protected function configure(array $attributes): void
    {
        if ($this->getBean()->get('ImportType_Code')) {
            $importer = new TeslaImporter($this->getBean());
            $importer->setTranslator($this->getTranslator());
            $importer->setup($attributes);
            if (!$importer->getValidationHelper()->hasError()) {
                $this->save($attributes);
            }
            $this->getValidationHelper()->merge($importer->getValidationHelper());
        }
    }


}
