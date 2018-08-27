<?php

declare(strict_types=1);

namespace Intervention\Image\Vips;

use Intervention\Image\AbstractColor;
use Intervention\Image\Exception\NotSupportedException;

class Color extends AbstractColor
{
    /**
     * @var int
     */
    public $red;

    /**
     * @var int
     */
    public $green;

    /**
     * @var int
     */
    public $blue;

    /**
     * @var int
     */
    public $alpha;

    /**
     * Initiate the color from the integer.
     *
     * @param  int  $value
     * @return void
     */
    public function initFromInteger($value): void
    {
        $this->alpha = ($value >> 24) & 0xFF;
        $this->red = ($value >> 16) & 0xFF;
        $this->green = ($value >> 8) & 0xFF;
        $this->blue = $value & 0xFF;
    }

    /**
     * Initiate the color from the array.
     *
     * @param  array  $value
     * @return void
     */
    public function initFromArray($value): void
    {
        $array = array_values($value);

        if (count($array) === 4) {
            list($red, $green, $blue, $alpha) = $array;

            $alpha = $this->alpha2vips($alpha);
        } elseif (count($array) === 3) {
            list($red, $green, $blue) = $array;

            $alpha = $this->alpha2vips(1);
        }

        $this->red = (int) $red;
        $this->green = (int) $green;
        $this->blue = (int) $blue;
        $this->alpha = $alpha;
    }

    /**
     * Initiate the color from the string.
     *
     * @param  string  $value
     * @return void
     */
    public function initFromString($value): void
    {
        if ($color = $this->rgbaFromString($value)) {
            $this->red = (int) $color[0];
            $this->green = (int) $color[1];
            $this->blue = (int) $color[2];
            $this->alpha = $this->alpha2vips($color[3]);
        }
    }

    /**
     * Initiate the color from the ImagickPixel object.
     *
     * @param  \ImagickPixel  $value
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function initFromObject($value): void
    {
        throw new NotSupportedException('VIPS color cannot be initiated from the ImagickPixel object.');
    }

    /**
     * Initiate the color from the RGB values.
     *
     * @param  int  $red
     * @param  int  $green
     * @param  int  $blue
     * @return void
     */
    public function initFromRgb($red, $green, $blue): void
    {
        $this->red = (int) $red;
        $this->green = (int) $green;
        $this->blue = (int) $blue;
        $this->alpha = $this->alpha2vips(1);
    }

    /**
     * Initiate the color from the RGBA values.
     *
     * @param  int  $red
     * @param  int  $green
     * @param  int  $blue
     * @param  float  $alpha
     * @return void
     */
    public function initFromRgba($red, $green, $blue, $alpha): void
    {
        $this->red = (int) $red;
        $this->green = (int) $green;
        $this->blue = (int) $blue;
        $this->alpha = $this->alpha2vips($alpha);
    }

    /**
     * Convert the color to integer.
     *
     * @return int
     */
    public function getInt(): int
    {
        return ($this->alpha << 24) + ($this->red << 16) + ($this->green << 8) + $this->blue;
    }

    /**
     * Convert the color to hexadecimal string.
     *
     * @param  string  $prefix
     * @return string
     */
    public function getHex($prefix): string
    {
        return sprintf('%s%02x%02x%02x', $prefix, $this->red, $this->green, $this->blue);
    }

    /**
     * Convert the color to array.
     *
     * @return array
     */
    public function getArray(): array
    {
        return [$this->red, $this->green, $this->blue, round($this->alpha / 255, 2)];
    }

    /**
     * Convert the color to RGBA string.
     *
     * @return string
     */
    public function getRgba(): string
    {
        return sprintf('rgba(%d, %d, %d, %.2F)', $this->red, $this->green, $this->blue, round($this->alpha / 255, 2));
    }

    /**
     * Check if the current color is different from the given color.
     *
     * @param  \Intervention\Image\AbstractColor  $color
     * @param  int  $tolerance
     * @return bool
     */
    public function differs(AbstractColor $color, $tolerance = 0): bool
    {
        $colorTolerance = round($tolerance * 2.55);
        $alphaTolerance = round($tolerance * 1.27);

        $delta = [
            'red'   => abs($color->red - $this->red),
            'green' => abs($color->green - $this->green),
            'blue'  => abs($color->blue - $this->blue),
            'alpha' => abs($color->alpha - $this->alpha),
        ];

        return $delta['red'] > $colorTolerance
            || $delta['green'] > $colorTolerance
            || $delta['blue'] > $colorTolerance
            || $delta['alpha'] > $alphaTolerance;
    }

    /**
     * Convert the RGBA value (0-1) to VIPS value (0-255).
     *
     * @param  float|string  $input
     * @return int
     */
    protected function alpha2vips($input): int
    {
        if ($input > 1 && $input <= 255) {
            return (int) $input;
        }

        if ($input > 255) {
            $input /= 255;
        }

        return (int) ceil($input * 255);
    }
}
