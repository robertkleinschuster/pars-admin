<?php

namespace Pars\Admin\Update;


use Pars\Admin\Base\BaseModel;
use Pars\Core\Database\Updater\DataUpdater;
use Pars\Core\Database\Updater\SchemaUpdater;
use Pars\Core\Database\Updater\SpecialUpdater;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;

class UpdateModel extends BaseModel
{

    public const OPTION_SCHEMA_ALLOWED = 'schema_allowed';
    public const OPTION_DATA_ALLOWED = 'data_allowed';
    public const OPTION_SPECIAL_ALLOWED = 'special_allowed';


    public function getSchemaUpdater()
    {
        return new SchemaUpdater($this->getDbAdpater());
    }

    public function getDataUpdater()
    {
        return new DataUpdater($this->getDbAdpater());
    }

    public function getSpecialUpdater()
    {
        return new SpecialUpdater($this->getDbAdpater());
    }


    /**
     * @param SubmitParameter $submitParameter
     * @param IdParameter $idParameter
     * @param array $attribute_List
     * @throws \Niceshops\Core\Exception\AttributeNotFoundException
     */
    public function handleSubmit(SubmitParameter $submitParameter, IdParameter $idParameter, array $attribute_List)
    {
        switch ($submitParameter->getMode()) {
            case 'schema':
                if ($this->hasOption(self::OPTION_SCHEMA_ALLOWED)) {
                    $schemaUpdater = new SchemaUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($attribute_List);
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
            case 'data':
                if ($this->hasOption(self::OPTION_DATA_ALLOWED)) {
                    $schemaUpdater = new DataUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($attribute_List);
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
            case 'special':
                if ($this->hasOption(self::OPTION_SPECIAL_ALLOWED)) {
                    $schemaUpdater = new SpecialUpdater($this->getDbAdpater());
                    $schemaUpdater->execute($attribute_List);
                    $this->getValidationHelper()->addErrorFieldMap($schemaUpdater->getValidationHelper()->getErrorFieldMap());
                } else {
                    $this->handlePermissionDenied();
                }
                break;
        }
    }
}
