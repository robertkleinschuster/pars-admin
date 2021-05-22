<?php

namespace Pars\Admin\Article;

use Pars\Admin\Base\BaseEdit;
use Pars\Component\Base\Form\FileSelect;
use Pars\Model\File\FileBeanList;

/**
 * Class ArticleEdit
 * @package Pars\Admin\Article
 */
abstract class ArticleEdit extends BaseEdit
{
    use ArticleComponentTrait;

    public bool $textOnly = false;
    public bool $translationOnly = false;
    public ?FileBeanList $fileBeanList = null;
    public ?array $domain_List = null;

    protected function initFieldsBefore()
    {
        parent::initFieldsBefore();
        $this->getForm()->addHidden('Locale_Code', $this->getTranslator()->getLocale());
    }

    protected function addFieldContactEmail(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('article.data.contact_email');
        }
        if (!$group) {
            $group = $this->getGroupByField('Article_Data[contact_email]');
        }
        $formGroup = $this->getForm()->addEmail(
            'Article_Data[contact_email]',
            '{Article_Data[contact_email]}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldVoteOnce(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('article.data.vote.once');
        }
        if (!$group) {
            $group = $this->getGroupByField('Article_Data[vote_once]');
        }
        $formGroup = $this->getForm()->addCheckbox(
            'Article_Data[vote_once]',
            '{Article_Data[vote_once]}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }

    /**
     * @param string|null $label
     * @param string|null $group
     * @return \Pars\Component\Base\Form\FormGroup
     * @throws \Pars\Bean\Type\Base\BeanException
     * @throws \Pars\Pattern\Exception\CoreException
     */
    protected function addFieldCodeInternal(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('article.code');
        }
        if (!$group) {
            $group = $this->getGroupByField('Article_Code');
        }
        $formGroup = $this->getForm()->addText(
            'Article_Code',
            '{Article_Code}',
            $label
        );
        $formGroup->setGroup($group);

        return $formGroup;
    }


    protected function addFieldName(string $label = null, string $group = null)
    {

        if (!$label) {
            $label = $this->translate('articletranslation.name');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Name');
        }
        $formGroup = $this->getForm()->addText(
            'ArticleTranslation_Name',
            '{ArticleTranslation_Name}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldTitle(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.title');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Title');
        }
        $formGroup = $this->getForm()->addText(
            'ArticleTranslation_Title',
            '{ArticleTranslation_Title}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldFooter(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.footer');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Footer');
        }
        $formGroup = $this->getForm()->addWysiwyg(
            'ArticleTranslation_Footer',
            '{ArticleTranslation_Footer}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }


    protected function addFieldHeading(string $label = null, string $group = null)
    {

        if (!$label) {
            $label = $this->translate('articletranslation.heading');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Heading');
        }
        $formGroup = $this->getForm()->addText(
            'ArticleTranslation_Heading',
            '{ArticleTranslation_Heading}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldSubHeading(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.subheading');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_SubHeading');
        }
        $formGroup = $this->getForm()->addText(
            'ArticleTranslation_SubHeading',
            '{ArticleTranslation_SubHeading}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldTeaser(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.teaser');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Teaser');
        }
        $formGroup = $this->getForm()->addWysiwyg(
            'ArticleTranslation_Teaser',
            '{ArticleTranslation_Teaser}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldText(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.text');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Text');
        }
        $formGroup = $this->getForm()->addWysiwyg(
            'ArticleTranslation_Text',
            '{ArticleTranslation_Text}',
            $label
        );
        $formGroup->setGroup($group);
        $formGroup->setHint($this->translate('articletranslation.text.hint'));
        return $formGroup;
    }


    protected function addFieldCodeUrl(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.code');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Code');
        }

        $formGroup = $this->getForm()->addText(
            'ArticleTranslation_Code',
            '{ArticleTranslation_Code}',
            $label
        );
        $formGroup->setHint($this->translate('articletranslation.code.hint'));
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFielHost(string $label = null, string $group = null)
    {

        if (!$label) {
            $label = $this->translate('articletranslation.host');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Host');
        }
        if ($this->hasDomain_List()) {
            $options = array_combine($this->getDomain_List(), $this->getDomain_List());
            $formGroup = $this->getForm()->addSelect(
                'ArticleTranslation_Host',
                $options,
                '{ArticleTranslation_Host}',
                $label
            );
        } else {
            $formGroup = $this->getForm()->addText(
                'ArticleTranslation_Host',
                '{ArticleTranslation_Host}',
                $label
            );
        }
        $formGroup->setHint($this->translate('articletranslation.host.hint'));
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldActive(string $label = null, string $group = null)
    {
        if (!$label) {
            $label = $this->translate('articletranslation.active');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Active');
        }
        $formGroup = $this->getForm()->addCheckbox(
            'ArticleTranslation_Active',
            '{ArticleTranslation_Active}',
            $label
        );
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldKeywords(string $label = null, string $group = null)
    {

        if (!$label) {
            $label = $this->translate('articletranslation.keywords');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Keywords');
        }
        $formGroup = $this->getForm()->addText(
            'ArticleTranslation_Keywords',
            '{ArticleTranslation_Keywords}',
            $label
        );
        $formGroup->setHint($this->translate('articletranslation.keywords.hint'));
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldPath(string $label = null, string $group = null)
    {

        if (!$label) {
            $label = $this->translate('articletranslation.path');
        }
        if (!$group) {
            $group = $this->getGroupByField('ArticleTranslation_Path');
        }
        $formGroup = $this->getForm()->addUrl(
            'ArticleTranslation_Path',
            '{ArticleTranslation_Path}',
            $label
        );
        $formGroup->setHint($this->translate('articletranslation.path.hint'));
        $formGroup->setGroup($group);
        return $formGroup;
    }

    protected function addFieldFile(string $label = null, string $group = null)
    {

        if (!$label) {
            $label = $this->translate('file.id');
        }
        if (!$group) {
            $group = $this->getGroupByField('File_ID');
        }
        $fileSelect = new FileSelect();
        $fileSelect->addFile($this->translate('noselection'), null);
        if ($this->hasFileBeanList()) {

            foreach ($this->getFileBeanList() as $file) {
                $fileSelect->addFile(
                    $file->get('File_Code') . '.' . $file->get('FileType_Code'),
                    $file->get('File_ID'),
                    '/u/' . $file->get('FileDirectory_Code')
                );
            }
        }
        $formGroup = $this->getForm()->addFileSelect('File_ID', $fileSelect, '{File_ID}', $label);
        $formGroup->setGroup($group);
        return $formGroup;
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
     * @return bool
     */
    protected function showNonTranslationFields(): bool
    {
        return !$this->isTranslationOnly();
    }

    /**
     * @return bool
     */
    protected function showNonTextFields(): bool
    {
        return !$this->isTextOnly();
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
