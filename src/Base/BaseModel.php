<?php

namespace Pars\Admin\Base;

use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Model\Authentication\User\UserBean;
use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Pars\Mvc\Model\AbstractModel;

abstract class BaseModel extends AbstractModel implements AdapterAwareInterface, TranslatorAwareInterface
{
    use AdapterAwareTrait;
    use TranslatorAwareTrait;

    /**
     * @var UserBean
     */
    private ?UserBean $user = null;

    /**
     * @return Adapter
     */
    public function getDbAdpater(): Adapter
    {
        return $this->adapter;
    }

    /**
     * @return UserBean
     */
    public function getUser(): UserBean
    {
        return $this->user;
    }

    /**
     * @param UserBean $user
     *
     * @return $this
     */
    public function setUser(UserBean $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasUser(): bool
    {
        return $this->user !== null;
    }


    protected function handlePermissionDenied()
    {
        $this->getValidationHelper()->addError('PermissionDenied', $this->translate('unauthorized.heading'));
        $this->getValidationHelper()->addError('Permission', $this->translate('permission.edit.denied'));
    }

    public function handleSubmit(SubmitParameter $submitParameter, IdParameter $idParameter, array $attribute_List)
    {
        switch ($submitParameter->getMode()) {
            case SubmitParameter::MODE_SAVE:
                $bean = $this->getBean();
                if ($bean->exists('Person_ID_Create')) {
                    if ($bean->get('Person_ID_Create') == $this->getUser()->get('Person_ID')) {
                        $this->addOption(self::OPTION_EDIT_ALLOWED);
                    }
                }
                break;
            case SubmitParameter::MODE_CREATE:
                break;
            case SubmitParameter::MODE_DELETE:
                $bean = $this->getBean();
                if ($bean->exists('Person_ID_Create')) {
                    if ($bean->get('Person_ID_Create') == $this->getUser()->get('Person_ID')) {
                        $this->addOption(self::OPTION_DELETE_ALLOWED);
                    }
                }
                break;
        }
        parent::handleSubmit($submitParameter, $idParameter, $attribute_List);
    }


    public function translate(string $code)
    {
        return $this->getTranslator()->translate($code, 'admin');
    }
}
