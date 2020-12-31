<?php


namespace Pars\Admin\File;


use Pars\Admin\Base\BaseEdit;

class FileEdit extends BaseEdit
{
    protected ?array $typeOptions = null;
    protected ?array $directoryOptions = null;

    protected function initialize()
    {
        if ($this->hasDirectoryOptions()) {
            $this->getForm()->addSelect('FileDirectory_ID', $this->getDirectoryOptions(), '{FileDirectory_ID}', $this->translate('filedirectory.id'));
        }
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect('FileType_Code', $this->getTypeOptions(), '{FileType_Code}', $this->translate('filetype.code'));
        }
        $this->getForm()->addText('File_Name', '{File_Name}', $this->translate('file.name'));
        $this->getForm()->addText('File_Code', '{File_Code}', $this->translate('file.code'));
        $this->getForm()->addFile('File_Upload', '{File_Upload}', $this->translate('file.upload'));

        parent::initialize();
    }

    /**
     * @return array
     */
    public function getTypeOptions(): array
    {
        return $this->typeOptions;
    }

    /**
     * @param array $typeOptions
     *
     * @return $this
     */
    public function setTypeOptions(array $typeOptions): self
    {
        $this->typeOptions = $typeOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTypeOptions(): bool
    {
        return isset($this->typeOptions);
    }


    /**
     * @return array
     */
    public function getDirectoryOptions(): array
    {
        return $this->directoryOptions;
    }

    /**
     * @param array $directoryOptions
     *
     * @return $this
     */
    public function setDirectoryOptions(array $directoryOptions): self
    {
        $this->directoryOptions = $directoryOptions;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasDirectoryOptions(): bool
    {
        return isset($this->directoryOptions);
    }


    protected function getRedirectController(): string
    {
        return 'file';
    }

    protected function getRedirectIdFields(): array
    {
        return [
            'File_ID'
        ];
    }

}
