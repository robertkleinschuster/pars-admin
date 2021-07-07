<?php

namespace Pars\Admin\Import;

use Pars\Bean\Type\Base\BeanListAwareInterface;
use Pars\Admin\Base\CrudModel;
use Pars\Core\Database\DatabaseBeanFinderTrait;
use Pars\Core\Database\DatabaseBeanProcessorTrait;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Helper\Validation\ValidationHelperAwareInterface;
use Pars\Import\ImportTask;
use Pars\Import\Tesla\TeslaImporter;
use Pars\Model\Cms\Page\CmsPageBeanFinder;
use Pars\Model\Import\Data\ImportDataBeanFinder;
use Pars\Model\Import\Data\ImportDataBeanProcessor;
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
        $this->setBeanProcessor(new ImportBeanProcessor($this->getDatabaseAdapter()));
        $finder = new ImportBeanFinder($this->getDatabaseAdapter());
        $finder->enhanceWithAvgs();
        $this->setBeanFinder($finder);
    }

    /**
     * @return array
     * @throws \Pars\Bean\Type\Base\BeanException
     */
    public function getImportTypeOptions(): array
    {
        $finder = new ImportTypeBeanFinder($this->getDatabaseAdapter());
        $finder->setImportType_Active(true);
        $options = [];
        foreach ($finder->getBeanListDecorator() as $item) {
            $options[$item->get('ImportType_Code')] = $this->translate('importtype.code.' . $item->get('ImportType_Code'));
        }
        return $options;
    }

    public function getArticleOptions(): array
    {
        $finder = new CmsPageBeanFinder($this->getDatabaseAdapter());
        $finder->filterLocale_Code($this->getTranslator()->getLocale());
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

    public function run()
    {
        $bean = $this->getBean();
        $importTask = new ImportTask([], new \DateTime(), $this->getContainer());
        $importTask->initById($bean->Import_ID);
        $importTask->execute();
    }
}
