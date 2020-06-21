<?php

namespace App\Processor;

use App\Configuration\Config;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageProcessor
{
    protected const HANDLERS = [
        IMAGETYPE_JPEG => [
            'load' => 'imagecreatefromjpeg',
            'save' => 'imagejpeg',
            'quality' => 100
        ],
        IMAGETYPE_PNG => [
            'load' => 'imagecreatefrompng',
            'save' => 'imagepng',
            'quality' => 0
        ],
        IMAGETYPE_GIF => [
            'load' => 'imagecreatefromgif',
            'save' => 'imagegif'
        ]
    ];

    protected string $imageDir = '';

    /**
     * Process Image.
     *
     * @param UploadedFile $file
     * @return array
     */
    public static function process(UploadedFile $file): array
    {
        $imageProcessor = new static;

        $imageProcessor->imageDir = Config::get('public_dir') . '/assets/images';

        $newFileName = uniqid('', true) . '.' . $file->guessExtension();
        $newFileNameThumb = uniqid('', true) . '_thumbnail.' . $file->guessExtension();

        try {
            $file->move(
                $imageProcessor->imageDir,
                $newFileName
            );

            $imageProcessor->createThumbnail($imageProcessor->imageDir . '/' . $newFileName, $imageProcessor->imageDir . '/' . $newFileNameThumb, 100, 100);

            return [
                'imageFull' => '/assets/images/' . $newFileName,
                'imageThumbnail' => '/assets/images/' . $newFileNameThumb
            ];

        } catch (FileException $fileException) {
            return null;
        }
    }

    /**
     * Create the thumbnail.
     *
     * @param $file
     * @param $dest
     * @param $targetWidth
     * @param null $targetHeight
     * @return mixed
     */
    protected function createThumbnail($file, $dest, $targetWidth, $targetHeight = null)
    {
        $type = exif_imagetype($file);

        if (!$type || !self::HANDLERS[$type]) {
            throw new FileException('There is a problem resizing the image.');
        }

        $image = call_user_func(self::HANDLERS[$type]['load'], $file);

        if (!$image) {
            throw new FileException('There is a problem resizing the image.');
        }

        $width = imagesx($image);
        $height = imagesy($image);

        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

        if ($type === IMAGETYPE_GIF || $type === IMAGETYPE_PNG) {

            imagecolortransparent(
                $thumbnail,
                imagecolorallocate($thumbnail, 0, 0, 0)
            );

            if ($type == IMAGETYPE_PNG) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
            }
        }

        if ($width > $height) {
            $y = 0;
            $x = ($width - $height) / 2;
            $smallestSide = $height;
        } else {
            $x = 0;
            $y = ($height - $width) / 2;
            $smallestSide = $width;
        }

        imagecopyresampled(
            $thumbnail,
            $image,
            0, 0, $x, $y,
            $targetWidth, $targetHeight,
            $smallestSide, $smallestSide
        );

        return call_user_func(
            self::HANDLERS[$type]['save'],
            $thumbnail,
            $dest,
            self::HANDLERS[$type]['quality']
        );
    }
}
