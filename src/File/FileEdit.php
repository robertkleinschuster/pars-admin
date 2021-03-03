<?php

namespace Pars\Admin\File;

use Pars\Admin\Base\BaseEdit;

class FileEdit extends BaseEdit
{
    protected ?array $typeOptions = null;
    protected ?array $directoryOptions = null;


    protected function initialize()
    {
        $this->getForm()->addFile('File_Upload', '{File_Upload}', $this->translate('file.upload'), 1, 1)
        ->setHint('Limit: ' . $this->file_upload_max_size() / 1024 / 1024 . ' MB');

        if ($this->hasDirectoryOptions()) {
            $this->getForm()->addSelect('FileDirectory_ID', $this->getDirectoryOptions(), '{FileDirectory_ID}', $this->translate('filedirectory.id'), 2, 1);
        }
        if ($this->hasTypeOptions()) {
            $this->getForm()->addSelect('FileType_Code', $this->getTypeOptions(), '{FileType_Code}', $this->translate('filetype.code'), 3, 1);
        }
        $this->getForm()->addText('File_Name', '{File_Name}', $this->translate('file.name'), 3, 2);
        $this->getForm()->addText('File_Code', '{File_Code}', $this->translate('file.code'), 3, 3);
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


    protected function file_upload_max_size()
    {
        static $max_size = -1;

        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = $this->parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }

            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = $this->parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }

    protected function parse_size($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }
}
