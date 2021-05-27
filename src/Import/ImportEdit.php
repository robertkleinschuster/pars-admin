<?php

namespace Pars\Admin\Import;

use Pars\Admin\Base\BaseEdit;

class ImportEdit extends BaseEdit
{
    protected ?array $importTypeOptions = null;
    protected ?array $articleOptions = null;

    protected function initialize()
    {
        $this->getForm()->setUseColumns(false);

        $this->getForm()->addText('Import_Name', '{Import_Name}', $this->translate('import.name'));
        if (null !== $this->getImportTypeOptions()) {
            $this->getForm()->addSelect('ImportType_Code', $this->getImportTypeOptions(), '{ImportType_Code}', $this->translate('importtype.code'));
        }
        if (null !== $this->getArticleOptions()) {
            $this->getForm()->addSelect('Article_ID', $this->getArticleOptions(), '{Article_ID}', $this->translate('article.id'));
        }
        $this->getForm()->addCheckbox('Import_Active', '{Import_Active}', $this->translate('import.active'), 2);
        $days = [];
        $days[null] = $this->translate('import.day.null');
        for ($i = 1; $i <= 7; $i++) {
            $days[$i] = $i;
        }
        $hours = [];
        $hours[null] = $this->translate('import.hour.null');
        for ($i = 0; $i <= 23; $i++) {
            $hours[$i] = $i;
        }
        $minutes = [];
        $minutes[null] = $this->translate('import.minute.null');
        for ($i = 0; $i <= 59; $i++) {
            $minutes[$i] = $i;
        }
        $this->getForm()->addSelect('Import_Day', $days, '{Import_Day}', $this->translate('import.day'));
        $this->getForm()->addSelect('Import_Hour', $hours, '{Import_Hour}', $this->translate('import.hour'));
        $this->getForm()->addSelect('Import_Minute', $minutes, '{Import_Minute}', $this->translate('import.minute'));
        parent::initialize();
    }

    /**
     * @return array|null
     */
    public function getImportTypeOptions(): ?array
    {
        return $this->importTypeOptions;
    }

    /**
     * @param array|null $importTypeOptions
     */
    public function setImportTypeOptions(?array $importTypeOptions): void
    {
        $this->importTypeOptions = $importTypeOptions;
    }

    /**
     * @return array|null
     */
    public function getArticleOptions(): ?array
    {
        return $this->articleOptions;
    }

    /**
     * @param array|null $articleOptions
     */
    public function setArticleOptions(?array $articleOptions): void
    {
        $this->articleOptions = $articleOptions;
    }


    protected function getRedirectController(): string
    {
        return 'import';
    }

    protected function getRedirectIdFields(): array
    {
        return ['Import_ID'];
    }
}
