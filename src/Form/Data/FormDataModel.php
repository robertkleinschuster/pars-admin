<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\CrudModel;
use Pars\Model\Form\Data\FormDataBeanFinder;
use Pars\Model\Form\Data\FormDataBeanProcessor;
use Pars\Model\Form\Field\FormFieldBeanFinder;
use Pars\Model\Form\FormBeanFinder;

class FormDataModel extends CrudModel
{
    public function initialize()
    {
        parent::initialize();
        $this->setBeanFinder(new FormDataBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new FormDataBeanProcessor($this->getParsContainer()));
    }

    public function getFieldList($formId, bool $onlyRequired = false)
    {
        $finder = new FormFieldBeanFinder($this->getDatabaseAdapter());
        $finder->filterValue('Form_ID', $formId);
        if ($onlyRequired) {
            $finder->filterValue('FormField_Required', true);
        }
        return $finder->getBeanListDecorator()->column('FormField_Code');
    }

    public function getForm_Code($formId)
    {
        $finder = new FormBeanFinder($this->getDatabaseAdapter());
        $finder->filterValue('Form_ID', $formId);
        if ($finder->count() == 1) {
            return $finder->getBean()->Form_Code;
        }
        return '';
    }

    public function getUnreadCount($formId)
    {
        $finder = new FormDataBeanFinder($this->getDatabaseAdapter());
        $finder->filterValue('FormData_Read', false);
        $finder->filterValue('Form_ID', $formId);
        return $finder->count();
    }

    public function save(array $attributes): void
    {
        parent::save($attributes);
    }


}
