<?php


namespace Pars\Admin\Form;


use Pars\Admin\Base\CrudModel;
use Pars\Model\Form\FormBeanFinder;
use Pars\Model\Form\FormBeanProcessor;
use Pars\Model\Form\Type\FormTypeBeanFinder;

class FormModel extends CrudModel
{
    public function initialize()
    {
        parent::initialize();
        $this->setBeanFinder(new FormBeanFinder($this->getDatabaseAdapter()));
        $this->setBeanProcessor(new FormBeanProcessor($this->getDatabaseAdapter()));
    }

    /**
     * @return array
     * @throws \Pars\Bean\Type\Base\BeanException
     * @throws \Pars\Pattern\Exception\CoreException
     */
    public function getFormTypeOption_List(): array
    {
        $options = [];
        $finder = new FormTypeBeanFinder($this->getDatabaseAdapter());
        $finder->filterValue('FormType_Active', true);
        foreach ($finder->getBeanListDecorator() as $bean) {
           $options[$bean->FormType_Code] = $this->translate('formtype.code.' . $bean->FormType_Code);
        }
        return $options;
    }

}
