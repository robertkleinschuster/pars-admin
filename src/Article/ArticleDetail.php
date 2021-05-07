<?php

namespace Pars\Admin\Article;

use Pars\Bean\Type\Base\BeanException;
use Pars\Helper\String\StringHelper;
use Pars\Pattern\Exception\AttributeExistsException;
use Pars\Pattern\Exception\AttributeLockException;
use Pars\Admin\Base\BaseDetail;
use Pars\Component\Base\Field\Button;
use Pars\Component\Base\Field\Icon;
use Pars\Component\Base\Toolbar\DropdownEditButton;
use Pars\Component\Base\Toolbar\EditTextButton;
use Pars\Component\Base\Toolbar\PreviewButton;
use Pars\Helper\Parameter\ContextParameter;
use Pars\Helper\Parameter\EditLocaleParameter;
use Pars\Helper\Parameter\IdParameter;
use Pars\Model\Article\DataBean;

abstract class ArticleDetail extends BaseDetail
{
    protected ?string $previewPath = null;

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     * @throws BeanException
     */
    protected function initialize()
    {
        parent::initialize();
        $this->initEditTextButton();
        $this->initPreviewButton();
    }

    protected function initName()
    {
        $this->setName('{ArticleTranslation_Name}');
    }


    /**
     * @throws BeanException
     */
    protected function initFields()
    {
        parent::initFields();
        $this->addSpan('Article_Code', $this->translate('article.code'))
            ->setGroup($this->translate('article.group.general'));
        $this->addSpan('ArticleTranslation_Code', $this->translate('articletranslation.code'))
            ->setGroup($this->translate('article.group.meta'));
        $this->addSpan('ArticleTranslation_Host', $this->translate('articletranslation.host'))
            ->setGroup($this->translate('article.group.general'));
        $this->addSpan('ArticleTranslation_Active', $this->translate('articletranslation.active'))
            ->setGroup($this->translate('article.group.visibility'));
        $this->addSpan('ArticleTranslation_Title', $this->translate('articletranslation.title'))
            ->setGroup($this->translate('article.group.meta'));
        $this->addSpan('ArticleTranslation_Heading', $this->translate('articletranslation.heading'))
            ->setGroup($this->translate('article.group.heading'));
        $this->addSpan('ArticleTranslation_SubHeading', $this->translate('articletranslation.subheading'))
            ->setGroup($this->translate('article.group.heading'));
        $this->addSpan('ArticleTranslation_Keywords', $this->translate('articletranslation.keywords'))
            ->setGroup($this->translate('article.group.meta'));
        $this->addSpan('ArticleTranslation_Footer', $this->translate('articletranslation.footer'))
            ->setGroup($this->translate('article.group.meta'));
        $this->addSpan('ArticleTranslation_Path', $this->translate('articletranslation.path'))
            ->setGroup($this->translate('article.group.additional'));
        $this->addSpan('ArticleTranslation_Teaser', $this->translate('articletranslation.teaser'))
            ->setGroup($this->translate('article.group.meta'));
        $this->addSpan('ArticleTranslation_Text', $this->translate('articletranslation.text'))
            ->setGroup($this->translate('article.group.text'));
    }

    /**
     * @throws BeanException
     */
    protected function initFieldsAfter()
    {
        if ($this->hasBean() && !$this->getBean()->empty('Article_Data')) {
            $data = $this->getBean()->get('Article_Data');
            if ($data instanceof DataBean) {
                foreach ($data as $key => $value) {
                    if (!is_array($value) && strpos($key, '__class') === false && isset($value)) {
                        switch ($data->type($key)) {
                            case DataBean::DATA_TYPE_BOOL:
                                if ($value === true) {
                                    $icon = new Icon(Icon::ICON_CHECK);
                                    $icon->addOption('text-success');
                                    $icon->setLabel($this->translate('article.data.' . $key));
                                    $icon->setGroup($this->translate('article.group.additional'));
                                    $this->pushField($icon);
                                } else {
                                    $icon = new Icon(Icon::ICON_X);
                                    $icon->addOption('text-danger');
                                    $icon->setLabel($this->translate('article.data.' . $key));
                                    $icon->setGroup($this->translate('article.group.additional'));
                                    $this->pushField($icon);
                                }
                                break;
                            case DataBean::DATA_TYPE_FLOAT:
                            case DataBean::DATA_TYPE_INT:
                            case DataBean::DATA_TYPE_STRING:
                                if ($value) {
                                    $this->addSpan("Article_Data[$key]", $this->translate('article.data.' . $key))
                                        ->setGroup($this->translate('article.group.additional'));
                                }
                                break;
                        }
                    }
                }
            }
        }
    }

    /**
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function initEditTextButton()
    {
        if ($this->isShowEdit()) {
            $button = new EditTextButton();
            $button->setModal(true);
            $button->setModalTitle($this->translate('edit.title'));
            $button->setPath($this->generateEditTextPath());
            $dropdown = new DropdownEditButton($button);
            if ($this->hasLocale_List()) {
                foreach ($this->getLocale_List() as $locale) {
                    $button = new Button();
                    $button->setModal(true);
                    $button->setModalTitle($this->translate('edit.title'));
                    $button->setContent($locale->get('Locale_Name'));
                    $button->setPath($this->generateEditTextPath($locale->get('Locale_UrlCode')));
                    $dropdown->getDropdownList()->push($button);
                }
            }
            $this->getToolbar()->push($dropdown);
        }
    }

    /**
     * @param string|null $locale_UrlCode
     * @return string
     * @throws AttributeExistsException
     * @throws AttributeLockException
     */
    protected function generateEditTextPath(string $locale_UrlCode = null): string
    {
        $path = $this->getPathHelper()
            ->setController($this->getEditController())
            ->setAction('edit_text')
            ->setId(IdParameter::fromMap($this->getEditIdFields()));
        if ($this->hasNextContext()) {
            $path->addParameter($this->getNextContext());
        }
        if ($locale_UrlCode) {
            $path->addParameter(new EditLocaleParameter($locale_UrlCode));
        }
        return $path->getPath();
    }

    /**
     * @throws BeanException
     */
    protected function initPreviewButton()
    {
        if ($this->hasPreviewPath()) {
            $this->getToolbar()->push((new PreviewButton($this->getPreviewPath()))->setTarget('_blank'));
        }
    }


    /**
     * @return string
     */
    public function getPreviewPath(): string
    {
        return $this->previewPath;
    }

    /**
     * @param string $previewPath
     *
     * @return $this
     */
    public function setPreviewPath(string $previewPath): self
    {
        $this->previewPath = $previewPath;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasPreviewPath(): bool
    {
        return isset($this->previewPath);
    }
}
