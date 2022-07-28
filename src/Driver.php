<?php

declare(strict_types=1);

namespace Intervention\Image\Vips;

use Intervention\Image\AbstractColor;
use Intervention\Image\AbstractDriver;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Image as InterventionImage;
use Jcupitt\Vips\BandFormat;
use Jcupitt\Vips\Extend;
use Jcupitt\Vips\Image as VipsImage;
use Jcupitt\Vips\Interpretation;

class Driver extends AbstractDriver
{
    /**
     * Create a new driver instance.
     *
     * @param  \Intervention\Image\Vips\Decoder  $decoder
     * @param  \Intervention\Image\Vips\Encoder  $encoder
     * @return void
     */
    public function __construct(Decoder $decoder = null, Encoder $encoder = null)
    {
        if (! $this->coreAvailable()) {
            throw new NotSupportedException('VIPS module is not available.');
        }

        $this->decoder = $decoder ?? new Decoder;
        $this->encoder = $encoder ?? new Encoder;
    }

    /**
     * Create a new image instance.
     *
     * @param  int  $width
     * @param  int  $height
     * @param  mixed  $background
     * @return \Intervention\Image\Image
     */
    public function newImage($width, $height, $background = null): InterventionImage
    {
        $background = new Color($background);

        $object = VipsImage::black(1, 1)
            ->add($background->red)
            ->cast(BandFormat::UCHAR)
            ->embed(0, 0, $width, $height, ['extend' => Extend::COPY])
            ->copy(['interpretation' => Interpretation::SRGB])
            ->bandjoin([
                $background->green,
                $background->blue,
                $background->alpha,
            ]);

        return new InterventionImage(new self, $object);
    }

    /**
     * Parse the color.
     *
     * @param  string  $value
     * @return \Intervention\Image\AbstractColor
     */
    public function parseColor($value): AbstractColor
    {
        return new Color($value);
    }

    /**
     * Check if the module is available.
     *
     * @return bool
     */
    protected function coreAvailable(): bool
    {
        if (! class_exists(VipsImage::class)) {
            return false;
        }

        return extension_loaded('ffi') || extension_loaded('vips');
    }
}
