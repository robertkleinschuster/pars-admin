<?php


namespace Pars\Admin\Article;


use Pars\Admin\Base\BaseEdit;
use Pars\Component\Base\Field\Paragraph;
use Pars\Model\Localization\Locale\LocaleBeanList;

abstract class ArticleEdit extends BaseEdit
{
    public ?array $fileOptions = null;

    public bool $textOnly = false;
    public bool $translationOnly = false;
    public ?LocaleBeanList $locale_List = null;

    protected function initialize()
    {
        $this->initFields();
        parent::initialize();
        $this->push(new Paragraph($this->translate('article.paragraph.hint')));
    }

    protected function initFields()
    {
        if (!$this->textOnly) {
            if (!$this->translationOnly) {
                $this->getForm()->addText('Article_Code', '{Article_Code}', $this->translate('article.code'), 1, 1)
                    ->setHint($this->translate('article.code.hint'));
            }

            $this->getForm()->addText('ArticleTranslation_Code', '{ArticleTranslation_Code}', $this->translate('articletranslation.code'), 2, 1)
                ->setHint($this->translate('articletranslation.code.hint'));
            $this->getForm()->addText('ArticleTranslation_Host', '{ArticleTranslation_Host}', $this->translate('articletranslation.host'), 2, 2)
                ->setHint($this->translate('articletranslation.host.hint'));
            $this->getForm()->addCheckbox('ArticleTranslation_Active', '{ArticleTranslation_Active}', $this->translate('articletranslation.active'), 6, 3);
            $this->getForm()->addText('ArticleTranslation_Keywords', '{ArticleTranslation_Keywords}', $this->translate('articletranslation.keywords'), 7, 2)
                ->setHint($this->translate('articletranslation.keywords.hint'));
            $this->getForm()->addUrl('ArticleTranslation_Path', '{ArticleTranslation_Path}', $this->translate('articletranslation.path'), 7, 1)
                ->setHint($this->translate('articletranslation.path.hint'));
            if ($this->hasFileOptions()) {
                $this->getForm()->addSelect('File_ID', $this->getFileOptions(), '{File_ID}', $this->translate('file.id'), 8, 1)
                    ->setHint($this->translate('file.id.hint'));
            }
        }
        $this->getForm()->addText('ArticleTranslation_Name', '{ArticleTranslation_Name}', $this->translate('articletranslation.name'), 4, 1);
        $this->getForm()->addText('ArticleTranslation_Title', '{ArticleTranslation_Title}', $this->translate('articletranslation.title'), 4, 2);
        $this->getForm()->addText('ArticleTranslation_Heading', '{ArticleTranslation_Heading}', $this->translate('articletranslation.heading'), 5, 1);
        $this->getForm()->addText('ArticleTranslation_SubHeading', '{ArticleTranslation_SubHeading}', $this->translate('articletranslation.subheading'), 5, 2);
        $this->getForm()->addWysiwyg('ArticleTranslation_Teaser', '{ArticleTranslation_Teaser}', $this->translate('articletranslation.teaser'), 9, 1);
        $this->getForm()->addWysiwyg('ArticleTranslation_Text', '{ArticleTranslation_Text}', $this->translate('articletranslation.text'), 10, 1);
        $this->getForm()->addWysiwyg('ArticleTranslation_Footer', '{ArticleTranslation_Footer}', $this->translate('articletranslation.footer'), 9, 2);
        $this->getForm()->addHidden('Locale_Code', $this->getTranslator()->getLocale());
    }

    /**
     * @param bool $textOnly
     */
    public function setTextOnly(bool $textOnly): void
    {
        $this->textOnly = $textOnly;
    }


    /**
     * @return array
     */
    public function getFileOptions(): array
    {
        return $this->fileOptions;
    }

    /**
     * @param array $fileOptions
     *
     * @return $this
     */
    public function setFileOptions(array $fileOptions): self
    {
        $this->fileOptions = $fileOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasFileOptions(): bool
    {
        return isset($this->fileOptions);
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

    /**
     * @param bool $translationOnly
     */
    public function setTranslationOnly(bool $translationOnly): void
    {
        $this->translationOnly = $translationOnly;
    }



}
