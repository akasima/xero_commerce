<?php

namespace Xpressengine\Plugins\XeroCommerce\Handlers;

use Illuminate\Http\UploadedFile;
use Xpressengine\Media\Thumbnailer;
use Xpressengine\Storage\File;

class XeroCommerceImageHandler
{
    const XERO_COMMERCE_IMAGE_PATH = 'public/xero_commerce/';

    protected $imageManager;

    public function __construct()
    {
        $this->imageManager = Thumbnailer::getManager();
    }

    public function resizeAfterSave(UploadedFile $file, $width, $height, $path, $name = null)
    {
        $img = $this->resize($file, $width, $height);

        $saveImage = $this->saveImage($file, $path, $img, $name);

        return $saveImage;
    }

    public function resize(UploadedFile $file, $width, $height)
    {
        $img = $this->imageManager->make($file->getRealPath());
        $img = $img->fit($width, $height);

        return $img;
    }

    public function saveImage(UploadedFile $file, $path, $img = null, $name = null)
    {
        if ($img == null) {
            $img = $this->imageManager->make($file->getRealPath());
        }

        $file = app('xe.storage')->create(
            $img->encode()->getEncoded(),
            self::XERO_COMMERCE_IMAGE_PATH . $path,
            $name ?: $file->getClientOriginalName()
        );

        return $file;
    }

    public function removeFile($fileId)
    {
        if ($fileId !== null) {
            $oldFile = app('xe.storage')->find($fileId);
            if ($oldFile) {
                app('xe.storage')->delete($oldFile);
            }
        }
    }

    public function getImageUrlByFileId($fileId)
    {
        $media = app('xe.media');
        $file = File::where('id', $fileId)->get()->first();

        if ($file == null) {
            return '';
        }

        return $media->make($file)->url();
    }
}
