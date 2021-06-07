<?php

namespace Pars\Admin\Base;

use Pars\Bean\Type\Base\BeanException;
use Pars\Component\Base\Alert\Alert;
use Pars\Component\Base\Field\Span;
use Pars\Core\Cache\ParsCache;
use Pars\Helper\Parameter\PaginationParameter;
use Pars\Model\Article\Translation\ArticleTranslationBean;
use Pars\Model\Authentication\User\UserBean;
use Pars\Model\Authentication\User\UserBeanFinder;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Pattern\Exception\AttributeNotFoundException;

/**
 * Class CrudModel
 * @package Pars\Admin\Base
 */
abstract class CrudModel extends BaseModel
{
    /**
     * @var int|null
     */
    protected ?int $currentPage = null;

    /**
     * @param int $personID
     * @return UserBean
     * @throws BeanException
     */
    public function getUserById(int $personID): UserBean
    {
        static $userBeans = [];
        if (!isset($userBeans[$personID])) {
            $userFinder = new UserBeanFinder($this->getDatabaseAdapter());
            $userFinder->setPerson_ID($personID);
            $userBeans[$personID] = $userFinder->getBean();
        }
        return $userBeans[$personID];
    }

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function lockEntry(string $hash)
    {
        $lockId = $this->getLockState($hash);
        if ($lockId) {
            $heading = $this->translate('dblocked.heading');
            $message = $this->translate('dblocked.message', ['name' => $this->getUserById($lockId)->User_Displayname]);
            $this->getValidationHelper()->addGeneralError("<h4>$heading</h4>$message");
            return false;
        } else {
            $this->getDatabaseAdapter()->getLock()->lock($this->getUserBean()->Person_ID, $hash);
            return true;
        }
    }

    /**
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function unlockEntry()
    {
        $this->getDatabaseAdapter()->getLock()->release($this->getUserBean()->Person_ID);
    }

    /**
     * @return bool
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getLockState(string $hash)
    {
        $id = $this->getDatabaseAdapter()->getLock()->has($hash);
        return $id && $id != $this->getUserBean()->Person_ID;
    }


    /**
     * @param PaginationParameter $paginationParameter
     * @return mixed|void
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws AttributeNotFoundException
     */
    public function handlePagination(PaginationParameter $paginationParameter)
    {
        if ($this->hasCurrentPage()) {
            $paginationParameter->setPage($this->getCurrentPage());
        }
        parent::handlePagination($paginationParameter);
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     *
     * @return $this
     */
    public function setCurrentPage(int $currentPage): self
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasCurrentPage(): bool
    {
        return isset($this->currentPage);
    }

    /**
     * @param ArticleTranslationBean|null $bean
     * @return string
     * @throws BeanException
     * @throws \Pars\Mvc\Exception\NotFoundException
     */
    public function generatePreviewPath(ArticleTranslationBean $bean = null)
    {
        if (empty($bean)) {
            $bean = $this->getBean();
        }
        $code = $bean->get('ArticleTranslation_Code');
        if ($code == '/') {
            $code = '';
        }
        $key = $this->getConfig()->getSecret();
        if (!$bean->empty('ArticleTranslation_Host')) {
            return '//'
                . $bean->get('ArticleTranslation_Host')
                . '/'
                . $this->getUserBean()->getLocale()->getUrl_Code()
                . "/$code?clearcache=$key";
        } else {
            return "//" . $this->getConfigValue('frontend.domain')
                . '/'
                . $this->getUserBean()->getLocale()->getUrl_Code()
                . "/$code?clearcache=$key";
        }
    }
}
