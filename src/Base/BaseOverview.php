<?php

namespace Pars\Admin\Base;

use Pars\Bean\Type\Base\BeanException;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Form\Hidden;
use Pars\Component\Base\Modal\Modal;
use Pars\Component\Base\Overview\DeleteButton;
use Pars\Component\Base\Overview\EditButton;
use Pars\Component\Base\Overview\MoveDownButton;
use Pars\Component\Base\Overview\Overview;
use Pars\Component\Base\Toolbar\CreateButton;
use Pars\Component\Base\Toolbar\CreateNewButton;
use Pars\Component\Base\Toolbar\DeleteBulkButton;
use Pars\Component\Base\Toolbar\LinkButton;
use Pars\Helper\Parameter\IdListParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\MoveParameter;
use Pars\Helper\Parameter\OrderParameter;
use Pars\Helper\Parameter\RedirectParameter;
use Pars\Helper\Parameter\SubmitParameter;
use Pars\Helper\Path\PathHelper;
use Pars\Mvc\View\Event\ViewEvent;
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
    public bool $showLink = false;
    public bool $showMove = false;
    public bool $showOrder = false;
    public ?string $token = null;
    public ?string $tokenName = null;

    protected function initAdditionalBefore()
    {
        parent::initAdditionalBefore();
        $this->setId($this->getElementClass() . $this->getControllerRequest()->getHash());
    }


    protected function handleAdditionalAfter()
    {
        parent::handleAdditionalAfter();
        foreach ($this->getFieldList() as $item) {
            if ($item instanceof MoveDownButton) {
                $redirect = (new RedirectParameter())
                    ->setPath($this->getPathHelper(false)->getCurrentPathReal());
                $path = $this->getPathHelper(false)
                    ->addParameter($redirect)
                    ->setController($this->getController())
                    ->setAction('repairOrder')->getPath();
                $icon = new Icon(Icon::ICON_REFRESH_CW);
                $icon->setEvent(ViewEvent::createLink($path));
                if ($this->hasId()) {
                    $icon->getEvent()->setTargetId($this->getId());
                }
                $item->setLabel($icon);
                $item->setLabelPath($path);
            }
        }
    }


    protected function handleAdditionalBefore()
    {
        parent::handleAdditionalBefore();
        $this->handleFormFields();
        $this->handleCreateButton();
        $this->handleLinkButton();
        $this->handleCreateNewButton();
        $this->handleDeleteBulkButton();
    }


    protected function handleFormFields()
    {
        if ($this->isShowDeleteBulk()) {
            $this->getMain()->setTag('form');
            $this->getMain()->setAttribute('method', 'post');
            $this->getMain()->setAttribute('action', $this->generateBulkAction());
        }
        if ($this->hasToken() && $this->hasTokenName()) {
            $this->getMain()->push(new Hidden($this->getTokenName(), $this->getToken()));
        }
        $redirect = new Hidden(
            RedirectParameter::name(),
            RedirectParameter::fromPath($this->generateRedirectPath(false))
        );
        $this->getMain()->push($redirect);
        $this->setBulkFieldName(IdListParameter::name());
        $this->setBulkFieldValue(IdListParameter::fromMap($this->getDetailIdFields()));
    }


    protected function handleCreateNewButton()
    {
        if ($this->isShowCreateNew()) {
            $this->getToolbar()->push(
                (new CreateNewButton($this->generateCreateNewPath()))
                    ->setModal(true)
                    ->setModalTitle($this->translate('create_new.title'))
            );
        }
    }

    protected function handleLinkButton()
    {
        if ($this->isShowLink()) {
            $this->getToolbar()->push(
                (new LinkButton($this->generateLinkPath()))
                    ->setModal(true)
                    ->setModalTitle($this->translate('link.title'))
            );
        }
    }

    protected function handleCreateButton()
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
     * @return string
     */
    protected function generateLinkPath(): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getController())
            ->setAction('link')
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
    protected function handleDeleteBulkButton()
    {
        if ($this->isShowDeleteBulk()) {
            $button = new DeleteBulkButton(SubmitParameter::name(), SubmitParameter::deleteBulk());
            $id = 'bulk_delete_' . $this->getController();
            if (!$this->getMain()->hasId()) {
                $this->getMain()->setId($id);
            }
            $button->setId($id . '__confirm_delete');
            $button->setEvent(ViewEvent::createCallback(function (DeleteBulkButton $element) use ($id) {
                $modal = new Modal();
                $modal->addInlineStyle('color', 'initial');
                $modal->addInlineStyle('display', 'block');
                $modal->addOption('show');
                $element->setTag('div');
                $element->setEvent(null);
                $modal->getModalBody()->setContent($this->translate('delete_bulk.message'));
                $modal->getModalTitle()->setContent($this->translate('delete_bulk.title'));
                $button = new Button();
                $button->setId($id . '__confirm');
                $button->setStyle(Button::STYLE_DANGER);
                $button->push(new Icon(Icon::ICON_CHECK));
                $path = null;
                if ($this->getMain()->hasAttribute('action')) {
                    $path = $this->getMain()->getAttribute('action');
                }
                $button->setEvent(ViewEvent::createSubmit($path, $id));
                if ($element->hasName() && $element->hasValue()) {
                    $element->push(new Hidden($element->getName(), $element->getValue()));
                }
                $button->getEvent()->setTargetId($id);
                $modal->getModalFooter()->push($button);
                $button = new Button();
                $button->setId($id . '__cancel');
                $button->setStyle(Button::STYLE_SECONDARY);
                $button->push(new Icon(Icon::ICON_X));
                $button->setEvent(ViewEvent::createLink());
                $button->getEvent()->setTargetId($id);
                $modal->getModalFooter()->push($button);
                $element->push($modal);
                $element->removeOption('d-none');
            }));
            $this->getToolbar()->push($button);
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
        if ($this->getControllerRequest()->hasId()) {
            $path->setId($this->getControllerRequest()->getId());
        }
        return $path->getPath();
    }

    /**
     * @return string
     */
    protected function getRedirectController(): string
    {
        if ($this->hasPathHelper()) {
            return $this->getPathHelper(false)->getController();
        }
        return $this->getController();
    }

    /**
     * @return string
     */
    protected function getRedirectAction(): string
    {
        if ($this->hasPathHelper()) {
            return $this->getPathHelper(false)->getAction();
        }
        return 'index';
    }

    /**
     * @return array
     */
    protected function getRedirectIdFields(): array
    {
        if ($this->hasPathHelper()) {
            return $this->getPathHelper(false)->getId()->getAttributes();
        }
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
    public function setToken(string $tokenName, string $token): self
    {
        $this->setTokenName($tokenName);
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
     * @return string
     */
    public function getTokenName(): string
    {
        return $this->tokenName;
    }

    /**
     * @param string $tokenName
     *
     * @return $this
     */
    public function setTokenName(string $tokenName): self
    {
        $this->tokenName = $tokenName;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTokenName(): bool
    {
        return isset($this->tokenName);
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
                    $field->setLabel($field->getLabel() . ' &darr;');
                }
                $order->setField($name);
                $path->addParameter($order);
            } elseif ($orgiginalOrder->hasMode()
                && $orgiginalOrder->getMode() == OrderParameter::MODE_DESC) {
                if ($orgiginalOrder->hasField() && $orgiginalOrder->getField() == $name) {
                    $field->setLabel($field->getLabel() . ' &uarr;');
                }
                $path->getOrder()->clear();
            } else {
                $order->setMode(OrderParameter::MODE_ASC);
                if ($orgiginalOrder->hasField() && $orgiginalOrder->getField() == $name) {
                    $field->setLabel($field->getLabel());
                }
                $order->setField($name);
                $path->addParameter($order);
            }

            $field->setLabelPath($path->getPath());
        }
        return $field;
    }

    /**
     * @return bool
     */
    public function isShowLink(): bool
    {
        return $this->showLink;
    }

    /**
     * @param bool $showLink
     * @return BaseOverview
     */
    public function setShowLink(bool $showLink): BaseOverview
    {
        $this->showLink = $showLink;
        return $this;
    }


}
