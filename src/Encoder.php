<?php

declare(strict_types=1);

namespace Intervention\Image\Vips;

use Intervention\Image\AbstractEncoder;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Image;

class Encoder extends AbstractEncoder
{
    public $interlace = false;

    /**
     * Get the encoded image as JPEG string.
     *
     * @return string
     */
    protected function processJpeg(): string
    {
        return $this->image
            ->getCore()
            ->writeToBuffer('.jpg', [
                'optimize_coding' => true,
                'strip'           => true,
                'interlace'       => $this->interlace,
                'Q'               => $this->quality,
            ]);
    }

    /**
     * Get the encoded image as PNG string.
     *
     * @return string
     */
    protected function processPng(): string
    {
        return $this->image
            ->getCore()
            ->writeToBuffer('.png', [
                'compression' => (int) round(9 - ($this->quality * 9 / 100) + 0.5),
                'interlace'   => $this->interlace,
                'strip'       => true,
            ]);
    }

    /**
     * Get the encoded image as WebP string.
     *
     * @return string
     */
    protected function processWebp(): string
    {
        return $this->image
            ->getCore()
            ->writeToBuffer('.webp', [
                'strip'     => true,
                'lossless'  => false,
                'Q'         => $this->quality,
            ]);
    }

    /**
     * Get the encoded image as GIF string.
     *
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processGif()
    {
        throw new NotSupportedException('GIF format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as TIFF string.
     *
     * @return void
     */
    protected function processTiff()
    {
        return $this->image
            ->getCore()
            ->writeToBuffer('.tiff', [
                'lossless'  => false,
                'Q'         => $this->quality,
            ]);
    }

    /**
     * Get the encoded image as BMP string.
     *
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processBmp()
    {
        throw new NotSupportedException('BMP format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as ICO string.
     *
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processIco()
    {
        throw new NotSupportedException('ICO format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as PSD string.
     *
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processPsd()
    {
        throw new NotSupportedException('PSD format is not supported by VIPS driver.');
    }

    public function process(Image $image, $format = null, $quality = null)
    {
        $parent = parent::process($image, $format, $quality);

        $this->interlace = false;

        return $parent;
    }
}
