<?php
namespace App\Tests\Integration;

use App\Services\ImageStorageService;
use App\Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * Class ServiceImageStorageServiceTest
 *
 * @package App\Tests\Integration
 */
class ServiceImageStorageServiceTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    public function testImageStorageServiceErrors()
    {
        $service = new ImageStorageService();

        try {
            $service->setDisk('aaa');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'IMAGESTORAGE_SERVICE_INVALID_DISK');
        }

        $test_params = [
            [],
            ['', ''],
            [['a', 500]],
            [['a', 0]],
            [[500, 'b']],
            [[0, 'b']]
        ];

        foreach ($test_params as $param) {
            try {
                $service->setThumbSizes($param);
            } catch (\Exception $e) {
                $this->assertTrue($e->getMessage() == 'IMAGESTORAGE_SERVICE_INVALID_THUMB_SIZE');
            }
        }

        $test_params = [
            [],
            ['', ''],
            ['a', 500],
            ['a', 0],
            [500, 'b'],
            [0, 'b']
        ];

        foreach ($test_params as $param) {
            try {
                $service->setOriginalSize($param);
            } catch (\Exception $e) {
                $this->assertTrue($e->getMessage() == 'IMAGESTORAGE_SERVICE_INVALID_ORIGINAL_SIZE');
            }
        }

        try {
            $service->delete('');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'IMAGESTORAGE_SERVICE_EMPTY_PATH');
        }

        try {
            $service->delete('file.jpg');
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'IMAGESTORAGE_SERVICE_FILE_NOT_EXISTS');
        }

        try {
            $service->saveOriginalImage();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'IMAGESTORAGE_SERVICE_EMPTY_PATH');
        }

        try {
            $service->saveThumbs();
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'IMAGESTORAGE_SERVICE_EMPTY_PATH');
        }

        $file = UploadedFile::fake()->image('file.xml');
        try {
            $service->upload($file, $file->getPath());
        } catch (\Exception $e) {
            $this->assertTrue($e->getMessage() == 'IMAGESTORAGE_SERVICE_INVALID_EXTENSION');
        }
    }

    public function testSetDiskMethod()
    {
        $service = new ImageStorageService();
        $service->reset();
        $current = $service->getDisk();
        $service->setDisk('local');

        $this->assertTrue($current !== 'local');
        $this->assertTrue($service->getDisk() === 'local');
    }

    public function testSetThumbSizesMethod()
    {
        $service = new ImageStorageService();
        $service->reset();
        $service->setThumbSizes([[10,10]]);
        $current = $service->getThumbSizes();

        $this->assertTrue($current[0][0] === 10);
        $this->assertTrue($current[0][1] === 10);
    }

    public function testSetOriginalSizeMethod()
    {
        $service = new ImageStorageService();
        $service->reset();
        $service->setOriginalSize([10,10]);
        $current = $service->getOriginalSize();

        $this->assertTrue($current[0] === 10);
        $this->assertTrue($current[1] === 10);
    }

    public function testSetBasePathMethod()
    {
        $service = new ImageStorageService();
        $service->reset();
        $service->setBasePath('/abc');
        $current = $service->getBasePath();

        $this->assertTrue($current === '/abc');
    }

    public function testUploadMethodSameRatio()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('image.jpg');
        $service = new ImageStorageService();
        $service->reset();
        $service->upload($file, $file->getPath());


        Storage::disk('public')->assertExists($service->getPath());
        $this->assertNotNull($service->getFullPath());
        $this->assertNotNull($service->getUrl());
    }

    public function testSaveOriginalImageMethodSameRatio()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('image.jpg');
        $service = new ImageStorageService();
        $service->reset();
        $service->upload($file, $file->getPath())
            ->setOriginalSize([10, 10])
            ->saveOriginalImage();


        $image = Image::make($service->getFullPath());
        $this->assertTrue($image->width() === 10);
        $this->assertTrue($image->height() === 10);
    }

    public function testSaveOriginalImageMethodWidthRatio()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('image.jpg', 15, 10);
        $service = new ImageStorageService();
        $service->reset();
        $service->upload($file, $file->getPath())
            ->setOriginalSize([15, 10])
            ->saveOriginalImage();


        $image = Image::make($service->getFullPath());
        $this->assertTrue($image->width() === 15);
        $this->assertTrue($image->height() === 10);
    }

    public function testSaveOriginalImageMethodHeightRatio()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('image.jpg', 10, 15);
        $service = new ImageStorageService();
        $service->reset();
        $service->upload($file, $file->getPath())
            ->setOriginalSize([10, 15])
            ->saveOriginalImage();


        $image = Image::make($service->getFullPath());
        $this->assertTrue($image->width() === 10);
        $this->assertTrue($image->height() === 15);
    }

    public function testSaveThumbsMethod()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('image.jpg');
        $service = new ImageStorageService();
        $service->reset();
        $service->upload($file, $file->getPath())
            ->setOriginalSize([100, 100])
            ->setThumbSizes([[10, 10]])
            ->saveOriginalImage()
            ->saveThumbs();


        $thumb_path = str_replace('org_', "thumb_10x10_", $service->getFullPath());
        $image = Image::make($thumb_path);

        $this->assertTrue($image->width() === 10);
        $this->assertTrue($image->height() === 10);
    }

    public function testDeleteMethod()
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('image.jpg', 100, 100);
        $service = new ImageStorageService();
        $service->reset();
        $service->upload($file, $file->getPath())
            ->setOriginalSize([100, 100])
            ->setThumbSizes([[10, 10]])
            ->saveOriginalImage()
            ->saveThumbs();


        $service->delete($service->getFullPath());
        Storage::disk('public')->assertMissing($service->getPath());
    }
}
