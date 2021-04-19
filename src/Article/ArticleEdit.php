<?php

namespace Pars\Admin\Article;

use Pars\Bean\Type\Base\BeanException;
use Pars\Admin\Base\BaseEdit;
use Pars\Component\Base\Field\Paragraph;
use Pars\Component\Base\Form\FileSelect;
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
            )->setHint($this->translate('article.code.hint'));
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
                $this->translate('articletranslation.text'),
                32
            )->setHint($this->translate('articletranslation.text.hint'));
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Teaser',
                '{ArticleTranslation_Teaser}',
                $this->translate('articletranslation.teaser'),
                33
            );
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Footer',
                '{ArticleTranslation_Footer}',
                $this->translate('articletranslation.footer'),
                34
            );
        } else {
            $this->getForm()->addText(
                'ArticleTranslation_Name',
                '{ArticleTranslation_Name}',
                $this->translate('articletranslation.name'),
                30
            );
            $this->getForm()->addText(
                'ArticleTranslation_Title',
                '{ArticleTranslation_Title}',
                $this->translate('articletranslation.title'),
                30,
                2
            );
            $this->getForm()->addText(
                'ArticleTranslation_Heading',
                '{ArticleTranslation_Heading}',
                $this->translate('articletranslation.heading'),
                31
            );
            $this->getForm()->addText(
                'ArticleTranslation_SubHeading',
                '{ArticleTranslation_SubHeading}',
                $this->translate('articletranslation.subheading'),
                31,
                2
            );
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Teaser',
                '{ArticleTranslation_Teaser}',
                $this->translate('articletranslation.teaser'),
                32
            );
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Text',
                '{ArticleTranslation_Text}',
                $this->translate('articletranslation.text'),
                33
            )->setHint($this->translate('articletranslation.text.hint'));
            $this->getForm()->addWysiwyg(
                'ArticleTranslation_Footer',
                '{ArticleTranslation_Footer}',
                $this->translate('articletranslation.footer'),
                32,
                2
            );
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
                $this->translate('articletranslation.code'),
                2
            )->setHint($this->translate('articletranslation.code.hint'));
            if ($this->hasDomain_List()) {
                $options = array_combine($this->getDomain_List(), $this->getDomain_List());
                $this->getForm()->addSelect(
                    'ArticleTranslation_Host',
                    $options,
                    '{ArticleTranslation_Host}',
                    $this->translate('articletranslation.host'),
                    2,
                    2
                )->setHint($this->translate('articletranslation.host.hint'));
            } else {
                $this->getForm()->addText(
                    'ArticleTranslation_Host',
                    '{ArticleTranslation_Host}',
                    $this->translate('articletranslation.host'),
                    2,
                    2
                )->setHint($this->translate('articletranslation.host.hint'));
            }

            $this->getForm()->addCheckbox(
                'ArticleTranslation_Active',
                '{ArticleTranslation_Active}',
                $this->translate('articletranslation.active'),
                3
            );
            $this->getForm()->addText(
                'ArticleTranslation_Keywords',
                '{ArticleTranslation_Keywords}',
                $this->translate('articletranslation.keywords'),
                34
            )->setHint($this->translate('articletranslation.keywords.hint'));
            $this->getForm()->addUrl(
                'ArticleTranslation_Path',
                '{ArticleTranslation_Path}',
                $this->translate('articletranslation.path'),
                35
            )->setHint($this->translate('articletranslation.path.hint'));
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
                $this->getForm()->addFileSelect('File_ID', $fileSelect, '{File_ID}', $this->translate('file.id'), 36, 1)
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
