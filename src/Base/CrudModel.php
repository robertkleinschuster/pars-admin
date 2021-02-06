<?php

namespace Pars\Admin\Base;

use Pars\Helper\Parameter\PaginationParameter;
use Pars\Model\Authentication\User\UserBean;
use Pars\Model\Authentication\User\UserBeanFinder;

abstract class CrudModel extends BaseModel
{
    protected ?int $currentPage = null;

    public function getUserById(int $personID): UserBean
    {
        $userFinder = new UserBeanFinder($this->getDbAdpater());
        $userFinder->setPerson_ID($personID);
        return $userFinder->getBean();
    }

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
