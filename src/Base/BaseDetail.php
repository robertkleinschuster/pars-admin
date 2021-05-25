<?php

namespace Pars\Admin\Base;

use Pars\Component\Base\Detail\Detail;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Toolbar\BackButton;
use Pars\Component\Base\Toolbar\DeleteButton;
use Pars\Component\Base\Toolbar\DropdownEditButton;
use Pars\Component\Base\Toolbar\EditButton;
use Pars\Helper\Parameter\EditLocaleParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Helper\Parameter\Parameter;
use Pars\Helper\Path\PathHelper;
use Pars\Model\Localization\Locale\LocaleBeanList;
use Pars\Mvc\View\Event\ViewEvent;

abstract class BaseDetail extends Detail implements CrudComponentInterface
{
    use CrudComponentTrait;

    public bool $showEdit = true;
    public bool $showDelete = true;
    public bool $showBack = true;
    protected ?LocaleBeanList $locale_List = null;

    protected function handleAdditionalBefore()
    {
        $this->initBackButton();
        $this->initEditButton();
        $this->initDeleteButton();
        parent::handleAdditionalBefore();
    }


    protected function initBackButton()
    {
        if ($this->isShowBack()) {
            $button = new BackButton();
            $button->setPath($this->generateIndexPath());
            $button->setEvent(ViewEvent::createLink($this->generateIndexPath()));
            $this->getToolbar()->push($button);
        }
    }

    protected function initEditButton()
    {
        if ($this->isShowEdit()) {
            $button = new EditButton($this->generateEditPath());
            $button->setEvent(ViewEvent::createModal($this->generateEditPath()));
            $button->setModal(true);
            $button->setModalTitle($this->translate('edit.title'));
            if ($this->hasShowEditFieldAccept()) {
                $button->setAccept($this->getShowEditFieldAccept());
            }
            if ($this->hasLocale_List()) {
                $dropdown = new DropdownEditButton($button);
                foreach ($this->getLocale_List() as $locale) {
                    $button = new Button();
                    $button->setModal(true);
                    $button->setModalTitle($this->translate('edit.title'));
                    $button->setContent($locale->get('Locale_Name'));
                    $button->setPath($this->generateEditPath($locale->get('Locale_UrlCode')));
                    $button->setEvent(ViewEvent::createLink());
                    $dropdown->getDropdownList()->push($button);
                }
                $this->getToolbar()->push($dropdown);
            } else {
                $this->getToolbar()->push($button);
            }
        }
    }

    protected function initDeleteButton()
    {
        if ($this->isShowDelete()) {
            $this->getToolbar()->push((new DeleteButton($this->generateDeletePath()))
                ->setModal(true)
                ->setModalTitle($this->translate('delete.title')));
        }
    }

    /**
     * @return string
     */
    protected function generateEditPath(string $locale_UrlCode = null): string
    {
        return $this->generateEditPathHelper($locale_UrlCode)->getPath();
    }

    /**
     * @return PathHelper
     */
    protected function generateEditPathHelper(string $locale_UrlCode = null): PathHelper
    {
        $path = $this->getPathHelper()
            ->setController($this->getEditController())
            ->setAction($this->getEditAction())
            ->setId(IdParameter::fromMap($this->getEditIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter($this->getNextContext());
        }
        if ($locale_UrlCode) {
            $path->addParameter(new EditLocaleParameter($locale_UrlCode));
        }
        return $path;
    }



    /**
     * @return string
     */
    protected function generateDeletePath(): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getEditController())
            ->setAction('delete')
            ->setId(IdParameter::fromMap($this->getEditIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter($this->getNextContext());
        }
        if ($this->hasCurrentContext()) {
            $param = new Parameter('prevcontext', $this->getCurrentContext()->toString());
            $path->addParameter($param);
        }
        return $path->getPath();
    }

    /**
     * @return string
     */
    protected function generateIndexPath(): string
    {
        if ($this->hasCurrentContext()) {
            return $this->getCurrentContext()->getPath();
        }
        $indexPath = $this->getPathHelper()
            ->setController($this->getIndexController())
            ->setAction($this->getIndexAction());
        $indexPath->setId(IdParameter::fromMap($this->getIndexIdFields()));
        return $indexPath->getPath();
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
     * @return BaseDetail
     */
    public function setShowDelete(bool $showDelete): BaseDetail
    {
        $this->showDelete = $showDelete;
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
     * @return BaseDetail
     */
    public function setShowEdit(bool $showEdit): BaseDetail
    {
        $this->showEdit = $showEdit;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowBack(): bool
    {
        return $this->showBack;
    }

    /**
     * @param bool $showBack
     * @return BaseDetail
     */
    public function setShowBack(bool $showBack): BaseDetail
    {
        $this->showBack = $showBack;
        return $this;
    }


    abstract protected function getIndexController(): string;

    abstract protected function getEditIdFields(): array;

    protected function getEditController(): string
    {
        return $this->getIndexController();
    }

    protected function getEditAction(): string
    {
        return 'edit';
    }


    protected function getIndexAction(): string
    {
        return 'index';
    }

    protected function getIndexIdFields(): array
    {
        return [];
    }


    /**
     * @return LocaleBeanList
     */
    public function getLocale_List(): LocaleBeanList
    {
        return $this->locale_List;
    }

    /**
     * @param LocaleBeanList $locale_List
     *
     * @return $this
     */
    public function setLocale_List(LocaleBeanList $locale_List): self
    {
        $this->locale_List = $locale_List;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasLocale_List(): bool
    {
        return isset($this->locale_List);
    }
}
