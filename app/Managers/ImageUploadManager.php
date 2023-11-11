<?php

namespace App\Managers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use RecursiveDirectoryIterator;


class ImageUploadManager
{
    public const DEFAULT_IMAGE = 'images/default.webp';
    public UploadedFile|string $file = '';
    public string $name = '';
    public string $path = '';
    public int $width = 0;
    public int $height = 0;
    public string $extension = 'webp';
    public int $quality = 60;


    /**
     * @return string
     */
    final public function upload(): string
    {
        $image_file_name = $this->name . '.' . $this->extension;
        $this->createDirectory();
        Image::make($this->file)
            ->fit($this->width, $this->height)
            ->save(public_path($this->path) . $image_file_name, $this->quality, $this->extension);
        return $image_file_name;
    }

    /**
     * @param string $path
     * @param string|null $img
     * @return void
     */
    final public static function deletePhoto(string $path, string|null $img): void
    {
        $path = public_path($path) . $img;
        if (!empty($img) && file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * @param UploadedFile|string $file
     * @return $this
     */
    final public function file(UploadedFile|string $file): self
    {
        $this->file = $file;
        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */

    final public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $path
     * @return $this
     */
    final public function path(string $path): self
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @param int $width
     * @return $this
     */
    final public function width(int $width): self
    {
        $this->width = $width;
        return $this;
    }

    /**
     * @param int $height
     * @return $this
     */
    final public function height(int $height): self
    {
        $this->height = $height;
        return $this;
    }

    /**
     * @param int $quality
     * @return $this
     */
    final public function quality(int $quality): self
    {
        $this->quality = $quality;
        return $this;
    }

    /**
     * @param string $extension
     * @return $this
     */
    final public function extension(string $extension): self
    {
        $this->extension = $extension;
        return $this;
    }

    /**
     * @return $this
     */
    final public function auto_size(): self
    {
        $this->height = Image::make($this->file)->height();
        $this->width  = Image::make($this->file)->width();
        return $this;
    }

    /**
     * @return $this
     */
    final public function original_extension(): self
    {
        $this->extension = $this->file->getClientOriginalExtension();
        return $this;
    }

    /**
     * @return void
     */
    final public function createDirectory(): void
    {
        $path = public_path($this->path);
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }
    }
     public function processImageUpload(
        string      $file,
        string      $name,
        string      $path,
        int         $width,
        int         $height,
        string      $path_thumb = null,
        int         $width_thumb = 0,
        int         $height_thumb = 0,
        string|null $existing_photo = ''
    ): string
    {
        if (!empty($existing_photo)) {
            self::deletePhoto($path, $existing_photo);
            if (!empty($path_thumb)) {
                self::deletePhoto($path_thumb, $existing_photo);
            }
        }
        $this->createDirectory();
        $photo_name = $this->uploadImage($name, $width, $height, $path, $file);
        if (!empty($path_thumb)) {
            $this->createDirectory($path_thumb);
            $this->uploadImage($name, $width_thumb, $height_thumb, $path_thumb, $file);
        }
        return $photo_name;
    }
    // public static function processImageUpload(
    //     string      $file,
    //     string      $name,
    //     string      $path,
    //     int         $width,
    //     int         $height,
    //     string      $path_thumb = null,
    //     int         $width_thumb = 0,
    //     int         $height_thumb = 0,
    //     string|null $existing_photo = ''
    // ): string
    // {
    //     if (!empty($existing_photo)) {
    //         self::deletePhoto($path, $existing_photo);
    //         if (!empty($path_thumb)) {
    //             self::deletePhoto($path_thumb, $existing_photo);
    //         }
    //     }
    //     self::createDirectory($path);
    //     $photo_name = self::uploadImage($name, $width, $height, $path, $file);
    //     if (!empty($path_thumb)) {
    //         self::createDirectory($path_thumb);
    //         self::uploadImage($name, $width_thumb, $height_thumb, $path_thumb, $file);
    //     }
    //     return $photo_name;
    // }

    /**
     * Upload the image.
     *
     * @param UploadedFile|string $file
     * @return string
     */
    final public function uploadImage(UploadedFile|string $file): string
    {
        $imageFileName = $this->name . '.' . $this->extension;
        $this->createDirectory();
        Image::make($file)
            ->fit($this->width, $this->height)
            ->save(public_path($this->path) . $imageFileName, $this->quality, $this->extension);
        return $imageFileName;
    }





    /**
     * @param string $path
     * @param string|null $image
     * @return string
     */
    final public static function prepareImageUrl(string $path, string|null $image): string
    {
        $url = url($path . $image);
        if (empty($image) || !File::exists(public_path($path . $image))) {
            $url = url(self::DEFAULT_IMAGE);
        }
        return $url;
    }



}
