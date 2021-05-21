<?php

namespace Pars\Admin\Article;

use Pars\Bean\Type\Base\BeanException;
use Pars\Admin\Base\BaseEdit;
use Pars\Component\Base\Field\Paragraph;
use Pars\Component\Base\Form\FileSelect;
use Pars\Helper\String\StringHelper;
use Pars\Model\File\FileBeanList;

/**
 * Class ArticleEdit
 * @package Pars\Admin\Article
 */
abstract class ArticleEdit extends BaseEdit
{
    public bool $textOnly = false;
    public bool $translationOnly = false;
    public ?FileBeanList $fileBeanList = null;
    public ?array $domain_List = null;
    /**
     * @throws BeanException
     */
    protected function initialize()
    {
        parent::initialize();
        $this->push(new Paragraph($this->translate('article.block.hint')));
    }

    protected function initFields()
    {
        $this->initAttributeFields();
        $this->initTranslationFields();
    }

    protected function initAttributeFields()
    {
        if (!$this->isTranslationOnly() && !$this->isTextOnly()) {
            $this->getForm()->addText(
                'Article_Code',
                '{Article_Code}',
                $this->translate('article.code')
            )->setHint($this->translate('article.code.hint'))
            ->setGroup($this->translate('article.group.general'));
        }
    }

    /**
     *
     */
    protected function initTranslationFields()
    {
        $this->initTranslationMetaFields();
        $this->initTranslationTextFields();
        $this->getForm()->addHidden('Locale_Code', $this->getTranslator()->getLocale());
    }

    /**
     *
     */
    protected function initTranslationTextFields()
    {
        if ($this->isTextOnly()) {

            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Text',
                '{ArticleTranslation_Text}',
                $this->translate('articletranslation.text')
            )->setHint($this->translate('articletranslation.text.hint'))
                ->setGroup($this->translate('article.group.text'));
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Teaser',
                '{ArticleTranslation_Teaser}',
                $this->translate('articletranslation.teaser')
            )->setGroup($this->translate('article.group.meta'));
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Footer',
                '{ArticleTranslation_Footer}',
                $this->translate('articletranslation.footer')
            )->setGroup($this->translate('article.group.meta_text'));
        } else {
            $this->getForm()->addText(
                'ArticleTranslation_Name',
                '{ArticleTranslation_Name}',
                $this->translate('articletranslation.name')
            )->setGroup($this->translate('article.group.general'));
            $this->getForm()->addText(
                'ArticleTranslation_Title',
                '{ArticleTranslation_Title}',
                $this->translate('articletranslation.title')
            )->setGroup($this->translate('article.group.meta'));
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Footer',
                '{ArticleTranslation_Footer}',
                $this->translate('articletranslation.footer')
            )->setGroup($this->translate('article.group.meta_text'));
            $this->getForm()->addText(
                'ArticleTranslation_Heading',
                '{ArticleTranslation_Heading}',
                $this->translate('articletranslation.heading')
            )->setGroup($this->translate('article.group.heading'));
            $this->getForm()->addText(
                'ArticleTranslation_SubHeading',
                '{ArticleTranslation_SubHeading}',
                $this->translate('articletranslation.subheading')
            )->setGroup($this->translate('article.group.heading'));
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Teaser',
                '{ArticleTranslation_Teaser}',
                $this->translate('articletranslation.teaser')
            )->setGroup($this->translate('article.group.meta_text'));
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Text',
                '{ArticleTranslation_Text}',
                $this->translate('articletranslation.text')
            )->setHint($this->translate('articletranslation.text.hint'))
                ->setGroup($this->translate('article.group.text'));

        }

    }

    /**
     *
     */
    protected function initTranslationMetaFields()
    {
        if (!$this->isTextOnly()) {
            $this->getForm()->addText(
                'ArticleTranslation_Code',
                '{ArticleTranslation_Code}',
                $this->translate('articletranslation.code')
            )->setHint($this->translate('articletranslation.code.hint'))
                ->setGroup($this->translate('article.group.meta'));
            if ($this->hasDomain_List()) {
                $options = array_combine($this->getDomain_List(), $this->getDomain_List());
                $this->getForm()->addSelect(
                    'ArticleTranslation_Host',
                    $options,
                    '{ArticleTranslation_Host}',
                    $this->translate('articletranslation.host')
                )->setHint($this->translate('articletranslation.host.hint'))
                    ->setGroup($this->translate('article.group.general'));
            } else {
                $this->getForm()->addText(
                    'ArticleTranslation_Host',
                    '{ArticleTranslation_Host}',
                    $this->translate('articletranslation.host')
                )->setHint($this->translate('articletranslation.host.hint'))
                    ->setGroup($this->translate('article.group.general'));
            }

            $this->getForm()->addCheckbox(
                'ArticleTranslation_Active',
                '{ArticleTranslation_Active}',
                $this->translate('articletranslation.active')
            )->setGroup($this->translate('article.group.visibility'));
            $this->getForm()->addText(
                'ArticleTranslation_Keywords',
                '{ArticleTranslation_Keywords}',
                $this->translate('articletranslation.keywords')
            )->setHint($this->translate('articletranslation.keywords.hint'))
                ->setGroup($this->translate('article.group.meta'));
            $this->getForm()->addUrl(
                'ArticleTranslation_Path',
                '{ArticleTranslation_Path}',
                $this->translate('articletranslation.path')
            )->setHint($this->translate('articletranslation.path.hint'))
                ->setGroup($this->translate('article.group.additional'));
            if ($this->hasFileBeanList()) {
                $fileSelect = new FileSelect();
                $fileSelect->addFile($this->translate('noselection'), null);
                foreach ($this->getFileBeanList() as $file) {
                    $fileSelect->addFile(
                        $file->get('File_Code') . '.' . $file->get('FileType_Code'),
                        $file->get('File_ID'),
                        '/u/' . $file->get('FileDirectory_Code')
                    );
                }
                $this->getForm()->addFileSelect('File_ID', $fileSelect, '{File_ID}', $this->translate('file.id'))
                    ->setHint($this->translate('file.id.hint'));
            }
        }
    }

    /**
     * @param bool $textOnly
     */
    public function setTextOnly(bool $textOnly): void
    {
        $this->textOnly = $textOnly;
    }

    /**
     * @param bool $translationOnly
     */
    public function setTranslationOnly(bool $translationOnly): void
    {
        $this->translationOnly = $translationOnly;
    }

    /**
     * @return bool
     */
    public function isTextOnly(): bool
    {
        return $this->textOnly;
    }

    /**
     * @return bool
     */
    public function isTranslationOnly(): bool
    {
        return $this->translationOnly;
    }


    /**
     * @return FileBeanList
     */
    public function getFileBeanList(): FileBeanList
    {
        return $this->fileBeanList;
    }

    /**
     * @param FileBeanList $fileBeanList
     *
     * @return $this
     */
    public function setFileBeanList(FileBeanList $fileBeanList): self
    {
        $this->fileBeanList = $fileBeanList;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasFileBeanList(): bool
    {
        return isset($this->fileBeanList);
    }


    /**
    * @return array
    */
    public function getDomain_List(): array
    {
        return $this->domain_List;
    }

    /**
    * @param array $domain_List
    *
    * @return $this
    */
    public function setDomain_List(?array $domain_List): self
    {
        $this->domain_List = $domain_List;
        return $this;
    }

    /**
    * @return bool
    */
    public function hasDomain_List(): bool
    {
        return isset($this->domain_List);
    }

}
