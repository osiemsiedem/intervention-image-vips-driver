<?php

declare(strict_types=1);

namespace Intervention\Image\Vips;

use Imagick;
use ImagickPixelException;
use Jcupitt\Vips\Exception;
use Jcupitt\Vips\Image as VipsImage;
use Intervention\Image\AbstractDecoder;
use Intervention\Image\Image as InterventionImage;

class Decoder extends AbstractDecoder
{
    /**
     * Create a new image from the path.
     *
     * @param  string  $path
     * @return \Intervention\Image\Image
     * @throws \Jcupitt\Vips\Exception
     */
    public function initFromPath($path): InterventionImage
    {
        $options = [];

        if ($accessMode = getenv('VIPS_ACCESS_MODE')) {
            $options['access'] = $accessMode;
        }

        $image = $this->initFromVips(VipsImage::newFromFile($path, $options));
        $image->setFileInfoFromPath($path);

        return $image;
    }

    /**
     * Create a new image from the binary string.
     *
     * @param  string  $data
     * @return \Intervention\Image\Image
     * @throws Exception
     */
    public function initFromBinary($data): InterventionImage
    {
        return $this->initFromVips(VipsImage::newFromBuffer($data));
    }

    /**
     * Create a new image from the VIPS object.
     *
     * @param  \Jcupitt\Vips\Image  $object
     * @return \Intervention\Image\Image
     */
    public function initFromVips(VipsImage $object): InterventionImage
    {
        return new InterventionImage(new Driver, $object);
    }

    /**
     * Create a new image from the GD resource.
     *
     * @param  \Resource  $resource
     * @return void
     * @throws Exception
     */
    public function initFromGdResource($resource)
    {
        ob_start();
        imagepng($resource);
        $stringdata = ob_get_clean();
        $sizes = getimagesize($stringdata);

        VipsImage::newFromMemory(
            $stringdata,
            $sizes[0],
            $sizes[1],
            $sizes['channels'],
            'png'
        );
    }

    /**
     * Create a new image from the Imagick object.
     *
     * @param  \Imagick  $object
     * @return void
     * @throws ImagickPixelException|Exception
     */
    public function initFromImagick(Imagick $object)
    {
        VipsImage::newFromMemory(
            $object->getImageBlob(),
            $object->getImageWidth(),
            $object->getImageHeight(),
            count($object->getImagePixelColor(0, 0)->getColor()),
            $object->getFormat()
        );
    }
}
