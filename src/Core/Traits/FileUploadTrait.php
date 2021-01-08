<?php

/*
 * This file is part of the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Core\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Trait FileUploadTrait.
 *
 * @ORM\HasLifecycleCallbacks()
 */
trait FileUploadTrait
{
    protected $file;
    /**
     * @ORM\Column(type="text",nullable=true)
     */
    protected $path;

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null,$removable= false)
    {
        if ($file == null && !$removable){
            $this->path=null;
        }


        $this->file     = $file;
        $this->updatedAt= new \DateTime();
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $path
     *
     * @return $this
     */
    public function setPath($path): self
    {

        $this->path = $path;

        return $this;
    }
    public function getRootDir()
    {
        return __DIR__;
    }
    public function getWebSubDir()
    {
        return 'uploads';
    }
    public function getWebDir()
    {
        return $this->getRootDir().\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'..'.\DIRECTORY_SEPARATOR.'public'.\DIRECTORY_SEPARATOR.$this->getWebSubDir();
    }
    abstract public function getUploadDir();
    public function getRelativePath()
    {
        return $this->getUploadDir().\DIRECTORY_SEPARATOR.$this->path;
    }
    public function getRelativeUrl()
    {
        return $this->getWebSubDir().\DIRECTORY_SEPARATOR.$this->getUploadDir().\DIRECTORY_SEPARATOR.$this->path;
    }
    public function getAbsolutePath()
    {
        if ($this->path) {
            return $this->getWebDir().\DIRECTORY_SEPARATOR.$this->getRelativePath();
        }

        return null;
    }
    public function getSizedImageAbsolutePath($size)
    {
        list($name, $extension) = explode('.', $this->path);

        return $this->getAbsoluteUploadDir().\DIRECTORY_SEPARATOR.$name.'_'.$size.'.'.$extension;
    }
    public function getAbsoluteUploadDir()
    {
        return $this->getWebDir().\DIRECTORY_SEPARATOR.$this->getUploadDir();
    }

    public function getFilters()
    {
        return ['_135x215', '_150x150', '_200x100', '_500x350', '_900x400'];
    }
    /**
     * @ORM\PrePersist
     * @ORM\PostUpdate
     * @ORM\PostUpdate
     * @ORM\PreUpdate
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        if (\in_array($this->getFile()->getMimeType(), $this->getAllowedTypes(), true)) {
            $name      = str_replace('.', '', uniqid('', true));
            $extension = $this->getExtension($this->getFile());

            if (!is_dir($this->getAbsoluteUploadDir())) {
                mkdir($this->getAbsoluteUploadDir(), 0777, true);
            }

            $this->deleteImages();

            $this->getFile()->move(
                $this->getAbsoluteUploadDir(),
                sprintf('%s.%s', $this->getNamer(), $extension)
            );

            $this->setPath($this->getNamer().'.'.$extension);
            $this->setFile(null,true);
        }
    }
    public function recurseRmdir($dir)
    {
        $files = array_diff(scandir($dir), ['.', '..']);
        foreach ($files as $file) {
            (is_dir("$dir/$file")) ? $this->recurseRmdir("$dir/$file") : unlink("$dir/$file");
        }

        return rmdir($dir);
    }
    public function deleteImages()
    {
        if ($this->path) {
            @unlink($this->getAbsolutePath());
            foreach ($this->getFilters() as $filter) {
                list($name, $extension) = explode('.', $this->getPath());
                @unlink($this->getAbsoluteUploadDir().\DIRECTORY_SEPARATOR.$name.$filter.'.'.$extension);
            }
        }
    }
    protected function getExtension(UploadedFile $file): ?string
    {
        $originalName = $file->getClientOriginalName();

        if ($extension = pathinfo($originalName, PATHINFO_EXTENSION)) {
            return $extension;
        }

        if ($extension = $file->guessExtension()) {
            return $extension;
        }

        return null;
    }
    abstract public function getNamer();
    abstract public function getAllowedTypes();
}
