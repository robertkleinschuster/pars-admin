<?php

namespace Pars\Admin\User;

use Pars\Admin\Base\CrudModel;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Model\Authentication\User\UserBeanFinder;
use Pars\Model\Authentication\User\UserBeanProcessor;
use Pars\Model\Authentication\UserState\UserStateBeanFinder;
use Pars\Model\Localization\Locale\LocaleBeanFinder;

/**
 * Class UserModel
 * @package Pars\Admin\Model
 * @method UserBeanFinder getBeanFinder() : BeanFinderInterface
 * @method UserBeanProcessor getProcessor() : BeanProcessorInterface
 */
class UserModel extends CrudModel
{

    public function initialize()
    {
        $this->setBeanFinder(new UserBeanFinder($this->getDbAdpater()));
        $this->setBeanProcessor(new UserBeanProcessor($this->getDbAdpater()));
        $this->getBeanProcessor()->setCurrentUserBean($this->getUserBean());
    }


    /**
     * @return array
     */
    public function getUserState_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new UserStateBeanFinder($this->getDbAdpater());

        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('UserState_Code')] = $this->translate('userstate.code.' . $bean->get('UserState_Code'));
        }
        return $options;
    }

    /**
     * @return array
     */
    public function getLocale_Options(bool $emptyElement = false): array
    {
        $options = [];
        if ($emptyElement) {
            $options[''] = $this->translate('noselection');
        }
        $finder = new LocaleBeanFinder($this->getDbAdpater());
        $finder->filterLocale_Active(true);

        foreach ($finder->getBeanListDecorator() as $bean) {
            $options[$bean->get('Locale_Code')] = $bean->get('Locale_Name');
        }
        return $options;
    }

    public function handleSubmit(SubmitParameter $submitParameter, IdParameter $idParameter, IdListParameter $idListParameter, array $attribute_List)
    {
        switch ($submitParameter->getMode()) {
            case SubmitParameter::MODE_SAVE:
                $bean = $this->getBean();
                if ($bean->exists('Person_ID')) {
                    if ($bean->get('Person_ID') == $this->getUserBean()->get('Person_ID')) {
                        $this->addOption(self::OPTION_EDIT_ALLOWED);
                    }
                }
                break;
        }
        parent::handleSubmit($submitParameter, $idParameter, $idListParameter, $attribute_List);
    }
}
