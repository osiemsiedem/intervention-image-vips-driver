<?php

declare(strict_types=1);

namespace Intervention\Image\Vips;

use Intervention\Image\AbstractEncoder;
use Intervention\Image\Exception\NotSupportedException;

class Encoder extends AbstractEncoder
{
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
                'interlace'       => false,
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
     * Get the encoded image as AVIF string.
     *
     * @return string
     */
    protected function processAvif()
    {
        return $this->image
            ->getCore()
            ->writeToBuffer('.avif', [
                'lossless'  => false,
                'Q'         => $this->quality,
            ]);
    }

    /**
     * Get the encoded image as Heic string.
     *
     * @return void
     *
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processHeic()
    {
        throw new NotSupportedException('HEIC format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as GIF string.
     *
     * @return void
     *
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
     *
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processTiff()
    {
        throw new NotSupportedException('TIFF format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as BMP string.
     *
     * @return void
     *
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
     *
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
     *
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processPsd()
    {
        throw new NotSupportedException('PSD format is not supported by VIPS driver.');
    }
}
