<?php

namespace Pars\Admin\Base;

use Pars\Bean\Processor\DefaultMetaFieldHandler;
use Pars\Bean\Processor\TimestampMetaFieldHandler;
use Pars\Bean\Type\Base\BeanException;
use Pars\Core\Cache\ParsCache;
use Pars\Core\Container\ParsContainer;
use Pars\Core\Container\ParsContainerAwareTrait;
use Pars\Core\Database\ParsDatabaseAdapter;
use Pars\Core\Database\ParsDatabaseAdapterAwareInterface;
use Pars\Core\Database\ParsDatabaseAdapterAwareTrait;
use Pars\Core\Translation\ParsTranslator;
use Pars\Core\Translation\ParsTranslatorAwareInterface;
use Pars\Core\Translation\ParsTranslatorAwareTrait;
use Pars\Pattern\Exception\AttributeNotFoundException;
use Pars\Core\Config\ParsConfig;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Model\Authentication\User\UserBean;
use Pars\Mvc\Exception\NotFoundException;
use Pars\Mvc\Model\AbstractModel;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;

/**
 * Class BaseModel
 * @package Pars\Admin\Base
 */
abstract class BaseModel extends AbstractModel implements
    LoggerAwareInterface,
    ParsDatabaseAdapterAwareInterface,
    ParsTranslatorAwareInterface
{
    use ParsContainerAwareTrait;
    use ParsDatabaseAdapterAwareTrait;
    use ParsTranslatorAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var UserBean|null
     */
    private ?UserBean $userBean = null;

    /**
     * @var ParsConfig|null
     */
    private ?ParsConfig $config = null;

    /**
     * @param ParsConfig|null $config
     * @return BaseModel
     */
    public function setConfig(?ParsConfig $config): BaseModel
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @return ParsContainer
     */
    public function getParsContainer(): ParsContainer
    {
        if (!$this->hasParsContainer()) {
            $this->setParsContainer($this->getContainer()->get(ParsContainer::class));
        }
        return $this->parsContainer;
    }

    public function getDatabaseAdapter(): ParsDatabaseAdapter
    {
        if (!$this->hasDatabaseAdapter()) {
            $this->setDatabaseAdapter($this->getParsContainer()->getDatabaseAdapter());
        }
        return $this->databaseAdapter;
    }

    public function initializeDependencies()
    {
        parent::initializeDependencies();
        if ($this->hasBeanProcessor()) {
            $processor = $this->getBeanProcessor();
            $processor->addMetaFieldHandler(
                new TimestampMetaFieldHandler('Timestamp_Edit', 'Timestamp_Create')
            );
            if ($this->hasUserBean()) {
                $processor->addMetaFieldHandler(
                    new DefaultMetaFieldHandler('Person_ID_Edit', $this->getUserBean()->Person_ID, true)
                );
                $processor->addMetaFieldHandler(
                    new DefaultMetaFieldHandler('Person_ID_Create', $this->getUserBean()->Person_ID)
                );
            }
            if ($processor instanceof ParsTranslatorAwareInterface) {
                $processor->setTranslator($this->getTranslator());
            }
        }
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        if (!$this->hasLogger()) {
            $this->setLogger($this->getParsContainer()->getLogger());
        }
        return $this->logger;
    }

    public function getTranslator(): ParsTranslator
    {
        if (!$this->hasTranslator()) {
            $this->setTranslator($this->getParsContainer()->getTranslator());
        }
        return $this->translator;
    }

    /**
     * @return bool
     */
    public function hasLogger(): bool
    {
        return isset($this->logger);
    }

    /**
     * @param string $code
     * @return ParsCache
     */
    public function getCache(string $code)
    {
        $cache = new ParsCache($code);
        if ($this->hasLogger()) {
            $cache->setLogger($this->getLogger());
        }
        return $cache;
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
     * @param string|null $key
     * @return mixed
     */
    public function getConfigValue(string $key = null)
    {
        if ($this->config === null) {
            return null;
        }
        if ($key == null) {
            return $this->config->toArray();
        }
        return $this->config->get($key);
    }

    public function getConfig()
    {
        if (!isset($this->config)) {
            $this->setConfig($this->getParsContainer()->getConfig());
        }
        return $this->config;
    }

    /**
     * @return array|null
     */
    public function getDomain_List(): ?array
    {
        $domains = $this->getConfigValue('domains');
        if ($domains) {
            $domains = explode(',', $domains);
            $domains = array_map('trim', $domains);
            if (count($domains)) {
                array_unshift($domains, '');
                return $domains;
            }
        }
        return null;
    }

    public function repairOrder()
    {
        if ($this->hasBeanOrderProcessor()) {
            $this->getBeanOrderProcessor()->repair($this->getBeanList());
        }
    }
}
