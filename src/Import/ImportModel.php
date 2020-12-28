<?php

namespace Pars\Admin\Import;

use League\OAuth2\Client\Provider\GenericProvider;
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

    protected function save(array $attributes): void
    {
        if (isset($attributes['tesla_username']) || isset($attributes['tesla_password'])) {
            $importer = new TeslaImporter($this->getBean());
            if ($importer->setup($attributes)) {
                parent::save($attributes);
            }
            $this->getValidationHelper()->merge($importer->getValidationHelper());
        } else {
            parent::save($attributes);
        }
    }



}
