<?php

namespace Pars\Admin\Base;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Niceshops\Core\Exception\AttributeNotFoundException;
use Pars\Helper\Parameter\PaginationParameter;
use Pars\Model\Authentication\User\UserBean;
use Pars\Model\Authentication\User\UserBeanFinder;

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
        $userFinder = new UserBeanFinder($this->getDbAdpater());
        $userFinder->setPerson_ID($personID);
        return $userFinder->getBean();
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

}
