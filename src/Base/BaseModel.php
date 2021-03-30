<?php

namespace Pars\Admin\Base;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Adapter\AdapterAwareInterface;
use Laminas\Db\Adapter\AdapterAwareTrait;
use Laminas\Db\Adapter\Exception\InvalidQueryException;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Niceshops\Bean\Processor\DefaultMetaFieldHandler;
use Niceshops\Bean\Processor\TimestampMetaFieldHandler;
use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Pars\Core\Config\ParsConfig;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Model\Authentication\User\UserBean;
use Pars\Model\Config\ConfigBeanFinder;
use Pars\Mvc\Exception\NotFoundException;
use Pars\Mvc\Model\AbstractModel;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

abstract class BaseModel extends AbstractModel implements AdapterAwareInterface, TranslatorAwareInterface, LoggerAwareInterface
{
    use AdapterAwareTrait;
    use TranslatorAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var UserBean|null
     */
    private ?UserBean $userBean = null;

    /**
     * @var ParsConfig|null
     */
    private ?ParsConfig $config = null;

    public function initializeDependencies()
    {
        parent::initializeDependencies();
        if ($this->hasBeanProcessor()) {
            $processor = $this->getBeanProcessor();
            $processor->addMetaFieldHandler(
                new TimestampMetaFieldHandler('Timestamp_Edit', 'Timestamp_Create')
            );
            $processor->addMetaFieldHandler(
                new DefaultMetaFieldHandler('Person_ID_Edit', $this->getUserBean()->Person_ID, true)
            );
            $processor->addMetaFieldHandler(
                new DefaultMetaFieldHandler('Person_ID_Create', $this->getUserBean()->Person_ID)
            );
            if ($processor instanceof TranslatorAwareInterface && $this->hasTranslator()) {
                $processor->setTranslator($this->getTranslator());
            }
        }
    }


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
    public function getUserBean(): UserBean
    {
        return $this->userBean;
    }

    /**
     * @param UserBean $user
     *
     * @return $this
     */
    public function setUserBean(UserBean $user): self
    {
        $this->userBean = $user;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasUserBean(): bool
    {
        return $this->userBean !== null;
    }


    protected function handlePermissionDenied()
    {
        $this->getValidationHelper()->addError('PermissionDenied', $this->translate('unauthorized.heading'));
        $this->getValidationHelper()->addError('Permission', $this->translate('permission.edit.denied'));
    }

    /**
     * @param SubmitParameter $submitParameter
     * @param IdParameter $idParameter
     * @param IdListParameter $idListParameter
     * @param array $attribute_List
     * @throws BeanException
     * @throws AttributeNotFoundException
     * @throws NotFoundException
     */
    public function handleSubmit(
        SubmitParameter $submitParameter,
        IdParameter $idParameter,
        IdListParameter $idListParameter,
        array $attribute_List
    ) {
        switch ($submitParameter->getMode()) {
            case SubmitParameter::MODE_SAVE:
                $bean = $this->getBean();
                if ($bean->exists('Person_ID_Create')) {
                    if ($bean->get('Person_ID_Create') == $this->getUserBean()->get('Person_ID')) {
                        $this->addOption(self::OPTION_EDIT_ALLOWED);
                    }
                }
                break;
            case SubmitParameter::MODE_CREATE:
                break;
            case SubmitParameter::MODE_DELETE:
                $bean = $this->getBean();
                if ($bean->exists('Person_ID_Create')) {
                    if ($bean->get('Person_ID_Create') == $this->getUserBean()->get('Person_ID')) {
                        $this->addOption(self::OPTION_DELETE_ALLOWED);
                    }
                }
                break;
        }
        parent::handleSubmit($submitParameter, $idParameter, $idListParameter, $attribute_List);
    }

    /**
     * @param string $code
     * @return string
     */
    public function translate(string $code): string
    {
        return $this->getTranslator()->translate($code, 'admin');
    }

    /**
     *
     */
    public function initConfig()
    {
        try {
            $finder = new ConfigBeanFinder($this->getDbAdpater());
            $this->config = $finder->getBeanList()->column('Config_Value', 'Config_Code');
        } catch (InvalidQueryException $exception) {
            $this->logger->error('DB Error initializing config.', ['exception' => $exception]);
        }
    }

    /**
     * @param string|null $key
     * @return mixed
     */
    public function getConfig(string $key = null)
    {
        if ($this->config === null) {
            $this->config = new ParsConfig($this->getDbAdpater(), 'admin');
        }
        if ($key == null) {
            return $this->config->toArray();
        }
        return $this->config->get($key);
    }
}
