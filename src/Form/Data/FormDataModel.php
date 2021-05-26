<?php


namespace Pars\Admin\Form\Data;


use Pars\Admin\Base\CrudModel;
use Pars\Model\Form\Data\FormDataBeanFinder;
use Pars\Model\Form\Data\FormDataBeanProcessor;
use Pars\Model\Form\Field\FormFieldBeanFinder;

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

}
