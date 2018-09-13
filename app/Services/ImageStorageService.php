<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Class ImageStorageService
 *
 * @package App\Services
 */
class ImageStorageService
{
    /**
     * @var string
     */
    protected $basePath = 'images';
    /**
     * @var array
     */
    protected $thumbSizes = [
        [250, 250],
        [100, 100]
    ];
    /**
     * @var array
     */
    protected $originalSize = [500, 500];

    /**
     * @var string
     */
    protected $disk = 'public';

    /**
     * @var array
     */
    private $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    /**
     * @var array
     */
    private $allowedMimes = ['image/jpeg', 'image/gif', 'image/png'];
    /**
     * @var
     */
    private $fullPath;
    /**
     * @var
     */
    private $path;
    /**
     * @var
     */
    private $url;

    /**
     * @return \App\Services\ImageStorageService
     */
    public function reset(): ImageStorageService
    {
        $this->basePath = 'images';
        $this->thumbSizes = [
            [250, 250],
            [100, 100]
        ];
        $this->originalSize = [500, 500];
        $this->fullPath = null;
        $this->path = null;
        $this->url = null;
        $this->disk = 'public';
        return $this;
    }

    /**
     * @return string
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * @param string $disk
     * @return $this
     * @throws \Exception
     */
    public function setDisk(string $disk)
    {
        $disks = Config::get("filesystems.disks");

        if (!array_key_exists($disk, $disks)) {
            throw new \Exception("IMAGESTORAGE_SERVICE_INVALID_DISK");
        }

        $this->disk = $disk;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getFullPath()
    {
        return $this->fullPath;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getThumbSizes(): array
    {
        return $this->thumbSizes;
    }

    /**
     * @param array $thumbSizes
     * @return \App\Services\ImageStorageService
     * @throws \Exception
     */
    public function setThumbSizes(array $thumbSizes): ImageStorageService
    {
        if (empty($thumbSizes)) {
            throw new \Exception("IMAGESTORAGE_SERVICE_INVALID_THUMB_SIZE");
        }
        foreach ($thumbSizes as $sizes) {
            list ($width, $heigth) = $sizes;
            if (!is_int($width) || !is_integer($heigth) || empty($width) || empty($heigth)) {
                throw new \Exception("IMAGESTORAGE_SERVICE_INVALID_THUMB_SIZE");
            }
        }
        $this->thumbSizes = $thumbSizes;
        return $this;
    }

    /**
     * @return array
     */
    public function getOriginalSize(): array
    {
        return $this->originalSize;
    }

    /**
     * @param array $originalSize
     * @return \App\Services\ImageStorageService
     * @throws \Exception
     */
    public function setOriginalSize(array $originalSize): ImageStorageService
    {
        if (empty($originalSize)) {
            throw new \Exception("IMAGESTORAGE_SERVICE_INVALID_ORIGINAL_SIZE");
        }
        list ($width, $heigth) = $originalSize;
        if (!is_int($width) || !is_integer($heigth) || empty($width) || empty($heigth)) {
            throw new \Exception("IMAGESTORAGE_SERVICE_INVALID_ORIGINAL_SIZE");
        }
        $this->originalSize = $originalSize;
        return $this;
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return $this->basePath;
    }

    /**
     * @param string $basePath
     * @return \App\Services\ImageStorageService
     */
    public function setBasePath(string $basePath): ImageStorageService
    {
        $this->basePath = $basePath;
        return $this;
    }

    /**
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null                   $path
     * @return \App\Services\ImageStorageService
     * @throws \Exception
     */
    public function upload(UploadedFile $file, string $path = null): ImageStorageService
    {
        if (!in_array($file->extension(), $this->allowedExtensions)) {
            throw new \Exception('IMAGESTORAGE_SERVICE_INVALID_EXTENSION');
        }
        if (!in_array($file->getMimeType(), $this->allowedMimes)) {
            throw new \Exception('IMAGESTORAGE_SERVICE_INVALID_MIME');
        }
        if (!empty($path)) {
            $path = '/' . trim($path, '/');
        }

        $file_name = uniqid("org_", true) . "." . $file->extension();
        $this->path = Storage::disk($this->disk)->putFileAs($this->getBasePath() . $path, $file, $file_name);
        $this->fullPath = Storage::disk($this->disk)->path($this->getBasePath() . $path) . '/' . $file_name;
        $this->url = Storage::url($this->path);

        return $this;
    }

    /**
     * @return \App\Services\ImageStorageService
     * @throws \Exception
     */
    public function saveOriginalImage(): ImageStorageService
    {
        if (empty($this->fullPath)) {
            throw new \Exception('IMAGESTORAGE_SERVICE_EMPTY_PATH');
        }

        $this->processImage($this->originalSize);

        return $this;
    }

    /**
     * @param array $size
     */
    private function processImage(array $size)
    {
        list($width, $height) = $size;
        $image = Image::make($this->fullPath);
        $this->resizeImage($image, $width, $height);
        $path = str_replace('org_', "thumb_{$width}x{$height}_", $this->fullPath);
        $image->save($path);
        $this->saveImageWithCanvas($width, $height, $path);
    }

    /**
     * @param \Intervention\Image\Image $image
     * @param                           $width
     * @param                           $height
     */
    private function resizeImage(\Intervention\Image\Image $image, $width, $height)
    {
        $ratio = $image->width() / $image->height();
        if ($ratio > 1) {
            $image->resize($width, null, function ($constraint) {
                $constraint->upsize();
                $constraint->aspectRatio();
            });
        } elseif ($ratio < 1) {
            $image->resize(null, $height, function ($constraint) {
                $constraint->upsize();
                $constraint->aspectRatio();
            });
        } else {
            $image->resize($width, $height, function ($constraint) {
                $constraint->upsize();
            });
        }
    }

    /**
     * @param $width
     * @param $height
     * @param $imagePath
     * @return \Intervention\Image\Image
     */
    private function saveImageWithCanvas($width, $height, $imagePath)
    {
        return Image::canvas($width, $height, '#ffffff')
            ->Insert($imagePath, 'center')
            ->save($imagePath, 80);
    }

    /**
     * @return \App\Services\ImageStorageService
     * @throws \Exception
     */
    public function saveThumbs(): ImageStorageService
    {
        if (empty($this->fullPath)) {
            throw new \Exception('IMAGESTORAGE_SERVICE_EMPTY_PATH');
        }

        foreach ($this->getThumbSizes() as $size) {
            $this->processImage($size);
        }

        return $this;
    }

    /**
     * @param $fullPath
     * @return bool
     * @throws \Exception
     */
    public function delete($fullPath)
    {
        if (empty($fullPath)) {
            throw new \Exception('IMAGESTORAGE_SERVICE_EMPTY_PATH');
        }
        if (!file_exists($fullPath)) {
            throw new \Exception('IMAGESTORAGE_SERVICE_FILE_NOT_EXISTS');
        }

        $all_sizes = $this->getThumbSizes();
        $all_sizes[] = $this->getOriginalSize();
        foreach ($all_sizes as $size) {
            list($width, $height) = $size;
            $path = str_replace('org_', "thumb_{$width}x{$height}_", $fullPath);
            unlink($path);
        }

        return unlink($fullPath);
    }
}
