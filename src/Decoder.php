<?php

declare(strict_types=1);

namespace Intervention\Image\Vips;

use Imagick;
use Jcupitt\Vips\Image as VipsImage;
use Intervention\Image\AbstractDecoder;
use Intervention\Image\Image as InterventionImage;
use Intervention\Image\Exception\NotSupportedException;

class Decoder extends AbstractDecoder
{
    /**
     * Create a new image from the path.
     *
     * @param  string  $path
     * @return \Intervention\Image\Image
     */
    public function initFromPath($path): InterventionImage
    {
        $options = [];

        if ($accessMode = \getenv('VIPS_ACCESS_MODE')) {
            $options['access'] = $accessMode;
        }

        return $this->initFromVips(VipsImage::newFromFile($path, $options));
    }

    /**
     * Create a new image from the binary string.
     *
     * @param  string  $data
     * @return \Intervention\Image\Image
     */
    public function initFromBinary($data): InterventionImage
    {
        return $this->initFromVips(VipsImage::newFromBuffer($data));
    }

    /**
     * Create a new image from the VIPS object.
     *
     * @param  \Jcupitt\Vips\Image  $resource
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
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function initFromGdResource($resource)
    {
        throw new NotSupportedException('VIPS driver cannot be initiated from the GD resource.');
    }

    /**
     * Create a new image from the Imagick object.
     *
     * @param  \Imagick  $object
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function initFromImagick(Imagick $imagick)
    {
        throw new NotSupportedException('VIPS driver cannot be initiated from the Imagick object.');
    }
}
