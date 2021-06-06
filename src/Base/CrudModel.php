<?php

namespace Pars\Admin\Base;

use Pars\Bean\Type\Base\BeanException;
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
