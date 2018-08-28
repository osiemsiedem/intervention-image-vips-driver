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
                'compression' => 9,
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
                'interlace' => false,
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
    protected function processGif(): void
    {
        throw new NotSupportedException('GIF format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as TIFF string.
     *
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processTiff(): void
    {
        throw new NotSupportedException('TIFF format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as BMP string.
     *
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processBmp(): void
    {
        throw new NotSupportedException('BMP format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as ICO string.
     *
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processIco(): void
    {
        throw new NotSupportedException('ICO format is not supported by VIPS driver.');
    }

    /**
     * Get the encoded image as PSD string.
     *
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    protected function processPsd(): void
    {
        throw new NotSupportedException('PSD format is not supported by VIPS driver.');
    }
}
