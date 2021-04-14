<?php

namespace Pars\Admin\Base;

use Pars\Bean\Type\Base\BeanException;
use Pars\Component\Base\Form\Hidden;
use Pars\Component\Base\Overview\DeleteButton;
use Pars\Component\Base\Overview\EditButton;
use Pars\Component\Base\Overview\Overview;
use Pars\Component\Base\Toolbar\CreateButton;
use Pars\Component\Base\Toolbar\CreateNewButton;
use Pars\Component\Base\Toolbar\DeleteBulkButton;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\OrderParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Helper\Path\PathHelper;
use Pars\Mvc\View\FieldInterface;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;

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
    public bool $showOrder = false;
    public ?string $token = null;


    protected function initAdditionalBefore()
    {
        parent::initAdditionalBefore();
        $this->initFormFields();
        $this->initCreateButton();
        $this->initCreateNewButton();
        $this->initDeleteBulkButton();
    }

    protected function initFormFields()
    {
        $this->setAttribute('method', 'post');
        $this->setAttribute('action', $this->generateBulkAction());
        if ($this->hasToken()) {
            $this->push(new Hidden('submit_token', $this->getToken()));
        }
        $redirect = new Hidden(
            RedirectParameter::name(),
            RedirectParameter::fromPath($this->generateRedirectPath(false))
        );
        $this->push($redirect);
        if ($this->isShowDeleteBulk()) {
            $this->setBulkFieldName(IdListParameter::name());
            $this->setBulkFieldValue(IdListParameter::fromMap($this->getDetailIdFields()));
        }

    }


    protected function initCreateNewButton()
    {
        if ($this->isShowCreateNew()) {
            $this->getToolbar()->push(
                (new CreateNewButton($this->generateCreateNewPath()))
                    ->setModal(true)
                    ->setModalTitle($this->translate('create_new.title'))
            );
        }
    }

    protected function initCreateButton()
    {
        if ($this->isShowCreate()) {
            $this->getToolbar()->unshift(
                (new CreateButton($this->generateCreatePath()))
                    ->setModal(true)
                    ->setModalTitle($this->translate('create.title'))
            );
        }
    }

    protected function handleFields()
    {
        if ($this->isShowDetail()) {
            $this->setDetailPath($this->generateDetailPath());
        }
        if ($this->isShowEdit()) {
            $this->setEditPath($this->generateEditPath());
        }
        if ($this->isShowDelete()) {
            $this->setDeletePath($this->generateDeletePath());
        }
        $this->handleMovePaths();
        parent::handleFields();
    }


    protected function handleEditButton(): EditButton
    {
        return parent::handleEditButton()->setModalTitle($this->translate('edit.title'));
    }

    protected function handleDeleteButton(): DeleteButton
    {
        return parent::handleDeleteButton()->setModalTitle($this->translate('delete.title'));
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
            $path->addParameter($this->getNextContext());
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
            $path->addParameter($this->getNextContext());
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
            $path->addParameter($this->getNextContext());
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
    protected function handleMovePaths()
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
            $path->addParameter($this->getNextContext());
        } elseif ($this->hasCurrentContext()) {
            $path->addParameter($this->getCurrentContext());
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
            $path->addParameter($this->getNextContext());
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
            $path->addParameter($this->getNextContext());
        }
        return $path->getPath();
    }

    /**
     * @return string
     */
    protected function generateBulkAction(): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getController())
            ->setAction('index');
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

    /**
     * @return bool
     */
    public function isShowOrder(): bool
    {
        return $this->showOrder;
    }

    /**
     * @param bool $showOrder
     * @return BaseOverview
     */
    public function setShowOrder(bool $showOrder): BaseOverview
    {
        $this->showOrder = $showOrder;
        return $this;
    }


    /**
     * @param string $name
     * @param string $label
     * @return \Pars\Component\Base\Field\Span
     */
    public function addFieldOrderable(string $name, string $label, FieldInterface $field = null)
    {
        static $orgiginalOrder = null;

        if ($field === null) {
            $field = $this->addFieldSpan($name, $label);
        }
        if ($this->hasPathHelperCurrent() && $this->isShowOrder()) {
            $path = clone $this->getPathHelperCurrent();
            $order = clone $path->getOrder();
            if ($orgiginalOrder === null) {
                $orgiginalOrder = clone $order;
            }
            if (
                $orgiginalOrder->hasMode()
                && $orgiginalOrder->getMode() == OrderParameter::MODE_ASC
            ) {
                $order->setMode(OrderParameter::MODE_DESC);
                if ($orgiginalOrder->hasField() && $orgiginalOrder->getField() == $name) {
                    $field->setLabel($field->getLabel() . ' &uarr;');
                }
            } else {
                $order->setMode(OrderParameter::MODE_ASC);
                if ($orgiginalOrder->hasField() && $orgiginalOrder->getField() == $name) {
                    $field->setLabel($field->getLabel() . ' &darr;');
                }
            }
            $order->setField($name);
            $path->addParameter($order);
            $field->setLabelPath($path->getPath());
        }
        return $field;
    }
}
