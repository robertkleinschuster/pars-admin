<?php


namespace Pars\Admin\Form\Field;


use Pars\Admin\Base\CrudModel;
use Pars\Bean\Processor\BeanOrderProcessor;
use Pars\Core\Database\AbstractDatabaseBeanFinder;
use Pars\Core\Database\AbstractDatabaseBeanProcessor;
use Pars\Model\Form\Field\FormFieldBeanFinder;
use Pars\Model\Form\Field\FormFieldBeanProcessor;
use Pars\Model\Form\Field\Type\FormFieldTypeBeanFinder;

/**
 * Class FormFieldModel
 * @package Pars\Admin\Form\Field
 */
class FormFieldModel extends CrudModel
{
    public function initialize()
    {
        parent::initialize();
        $this->setBeanFinder(new FormFieldBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new FormFieldBeanProcessor($this->getDatabaseAdapter()));
        $this->setBeanOrderProcessor(new BeanOrderProcessor(
            new FormFieldBeanProcessor($this->getDatabaseAdapter()),
            new FormFieldBeanFinder($this->getDatabaseAdapter()),
            'FormField_Order',
            'Form_ID'
        ));
    }

    public function getFormFieldTypeOption_List(): array
    {
        $options = [];
        $finder = new FormFieldTypeBeanFinder($this->getDatabaseAdapter());
        $finder->filterValue('FormFieldType_Active', true);
        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->FormFieldType_Code] = $this->translate('formfieldtype.code.' . $bean->FormFieldType_Code);
        }
        return $options;
    }

}
