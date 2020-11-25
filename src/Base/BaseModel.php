<?php

namespace Pars\Admin\Base;

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
        $this->getValidationHelper()->addError('Permission', $this->translate('permission.edit.denied'));
    }

    public function translate(string $code)
    {
        return $this->getTranslator()->translate($code, 'backoffice');
    }
}
