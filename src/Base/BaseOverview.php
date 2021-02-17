<?php

namespace Pars\Admin\Base;

use Niceshops\Bean\Type\Base\BeanException;
use Niceshops\Core\Exception\AttributeExistsException;
use Niceshops\Core\Exception\AttributeLockException;
use Pars\Component\Base\Form\Hidden;
use Pars\Component\Base\Overview\Overview;
use Pars\Component\Base\Toolbar\CreateNewButton;
use Pars\Component\Base\Toolbar\DeleteBulkButton;
use Pars\Helper\Parameter\ContextParameter;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Helper\Path\PathHelper;

/**
 * Class BaseOverview
 * @package Pars\Admin\Base
 */
abstract class BaseOverview extends Overview implements CrudComponentInterface
{
    use CrudComponentTrait;

    public bool $showEdit = true;
    public bool $showEditBulk = false;
    public bool $showDelete = true;
    public bool $showDeleteBulk = true;
    public bool $showDetail = true;
    public bool $showCreate = true;
    public bool $showCreateNew = false;
    public bool $showMove = false;
    public ?string $token = null;

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws BeanException
     */
    protected function initialize()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', $this->generateDeletePath());
        if ($this->hasToken()) {
            $this->push(new Hidden('submit_token', $this->getToken()));
        }
        $this->push(new Hidden(RedirectParameter::name(), RedirectParameter::fromPath($this->generateRedirectPath())));
        if ($this->hasSection()) {
            $this->setName($this->getSection());
        }
        if ($this->isShowDetail()) {
            $this->setDetailPath($this->generateDetailPath());
        }
        if ($this->isShowEdit()) {
            $this->setEditPath($this->generateEditPath());
        }
        if ($this->isShowDelete()) {
            $this->setDeletePath($this->generateDeletePath());
        }
        if ($this->isShowCreate()) {
            $this->getToolbar()->setCreatePath($this->generateCreatePath());
        }
        if ($this->isShowCreateNew()) {
            $this->getToolbar()->push((new CreateNewButton($this->generateCreateNewPath()))->setModal(true));
        }
        $this->setBulkFieldName(IdListParameter::name());
        $this->setBulkFieldValue(IdListParameter::fromMap($this->getDetailIdFields()));
        $this->initDeleteBulkButton();
        $this->initMovePaths();
        parent::initialize();
    }


    /**
     * @return string
     */
    protected function generateDetailPath(): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getController())
            ->setAction('detail')
            ->setId(IdParameter::fromMap($this->getDetailIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter(ContextParameter::fromPath($this->getNextContext()));
        }
        return $path->getPath();
    }

    /**
     * @return string
     */
    protected function generateCreatePath(): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getController())
            ->setAction('create')
            ->setId(IdParameter::fromMap($this->getCreateIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter(ContextParameter::fromPath($this->getNextContext()));
        }
        return $path->getPath();
    }

    /**
     * @return string
     */
    protected function generateCreateNewPath(): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getController())
            ->setAction('create_new')
            ->setId(IdParameter::fromMap($this->getCreateIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter(ContextParameter::fromPath($this->getNextContext()));
        }
        return $path->getPath();
    }

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws BeanException
     */
    protected function initDeleteBulkButton()
    {
        if ($this->isShowDeleteBulk()) {
            $button = new DeleteBulkButton(SubmitParameter::name(), SubmitParameter::deleteBulk());
            $button->setConfirm($this->translate('delete_bulk.message'));
            $button->addOption('d-none');
            $this->getToolbar()->push($button);
            $this->setTag('form');
        }
    }

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function initMovePaths()
    {
        if ($this->isShowMove()) {
            $path = $this->getPathHelper()
                ->setController($this->getController())
                ->setAction($this->getRedirectAction())
                ->setId(IdParameter::fromMap($this->getDetailIdFields()))
                ->addParameter(RedirectParameter::fromPath($this->generateRedirectPath(false)));
            $this->setMoveUpPath($path->clone()->addParameter(MoveParameter::up())->getPath());
            $this->setMoveDownPath($path->clone()->addParameter(MoveParameter::down())->getPath());
        }
    }

    /**
     * @param bool $context
     * @return PathHelper
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function generateRedirectPath(bool $context = true): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getRedirectController())
            ->setAction($this->getRedirectAction())
            ->setId(IdParameter::fromMap($this->getRedirectIdFields()));
        if ($this->hasNextContext() && $context) {
            $path->addParameter(ContextParameter::fromPath($this->getNextContext()));
        } elseif ($this->hasCurrentContext()) {
            $path->addParameter(ContextParameter::fromPath($this->getCurrentContext()));
        }
        return $path;
    }

    /**
     * @return string
     */
    protected function generateEditPath(): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getController())
            ->setAction('edit')
            ->setId(IdParameter::fromMap($this->getDetailIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter(ContextParameter::fromPath($this->getNextContext()));
        }
        return $path->getPath();
    }

    /**
     * @return string
     */
    protected function generateDeletePath(): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getController())
            ->setAction('delete')
            ->setId(IdParameter::fromMap($this->getDetailIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter(ContextParameter::fromPath($this->getNextContext()));
        }
        return $path->getPath();
    }

    /**
     * @return string
     */
    protected function getRedirectController(): string
    {
        return $this->getController();
    }

    /**
     * @return string
     */
    protected function getRedirectAction(): string
    {
        return 'index';
    }

    /**
     * @return array
     */
    protected function getRedirectIdFields(): array
    {
        return [];
    }

    /**
     * @return string
     */
    abstract protected function getController(): string;

    /**
     * @return array
     */
    abstract protected function getDetailIdFields(): array;

    /**
     * @return array
     */
    protected function getCreateIdFields(): array
    {
        return [];
    }

    /**
     * @return string
     */
    public function getSection(): string
    {
        return $this->getName();
    }

    /**
     * @param string $section
     *
     * @return $this
     */
    public function setSection(string $section): self
    {
        return $this->setName($section);
    }

    /**
     * @return bool
     */
    public function hasSection(): bool
    {
        return $this->hasName();
    }


    /**
     * @return bool
     */
    public function isShowCreate(): bool
    {
        return $this->showCreate;
    }

    /**
     * @param bool $showCreate
     * @return BaseOverview
     */
    public function setShowCreate(bool $showCreate): BaseOverview
    {
        $this->showCreate = $showCreate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowMove(): bool
    {
        return $this->showMove;
    }

    /**
     * @param bool $showMove
     */
    public function setShowMove(bool $showMove): void
    {
        $this->showMove = $showMove;
    }

    /**
     * @return bool
     */
    public function isShowDelete(): bool
    {
        return $this->showDelete;
    }

    /**
     * @param bool $showDelete
     * @return BaseOverview
     */
    public function setShowDelete(bool $showDelete): BaseOverview
    {
        $this->showDelete = $showDelete;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowDetail(): bool
    {
        return $this->showDetail;
    }

    /**
     * @param bool $showDetail
     * @return BaseOverview
     */
    public function setShowDetail(bool $showDetail): BaseOverview
    {
        $this->showDetail = $showDetail;
        return $this;
    }


    /**
     * @return bool
     */
    public function isShowEdit(): bool
    {
        return $this->showEdit;
    }

    /**
     * @param bool $showEdit
     * @return BaseOverview
     */
    public function setShowEdit(bool $showEdit): BaseOverview
    {
        $this->showEdit = $showEdit;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowEditBulk(): bool
    {
        return $this->showEditBulk;
    }

    /**
     * @param bool $showEditBulk
     */
    public function setShowEditBulk(bool $showEditBulk): void
    {
        $this->showEditBulk = $showEditBulk;
    }

    /**
     * @return bool
     */
    public function isShowDeleteBulk(): bool
    {
        return $this->showDeleteBulk;
    }

    /**
     * @param bool $showDeleteBulk
     */
    public function setShowDeleteBulk(bool $showDeleteBulk): void
    {
        $this->showDeleteBulk = $showDeleteBulk;
    }


    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return $this
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasToken(): bool
    {
        return isset($this->token);
    }

    /**
     * @return bool
     */
    public function isShowCreateNew(): bool
    {
        return $this->showCreateNew;
    }

    /**
     * @param bool $showCreateNew
     */
    public function setShowCreateNew(bool $showCreateNew): void
    {
        $this->showCreateNew = $showCreateNew;
    }
}
