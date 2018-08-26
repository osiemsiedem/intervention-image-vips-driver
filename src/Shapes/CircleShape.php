<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Shapes;

use Intervention\Image\Image;

class CircleShape extends EllipseShape
{
    /**
     * @var int
     */
    public $diameter = 100;

    /**
     * Create a new shape instance.
     *
     * @param  int|null  $diameter
     * @return void
     */
    public function __construct($diameter = null)
    {
        $this->width = is_numeric($diameter) ? (int) $diameter : $this->diameter;
        $this->height = is_numeric($diameter) ? (int) $diameter : $this->diameter;
        $this->diameter = is_numeric($diameter) ? (int) $diameter : $this->diameter;
    }

    /**
     * Draw the shape.
     *
     * @param  \Intervention\Image\Image  $image
     * @param  int  $x
     * @param  int  $y
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        throw new NotSupportedException('Circle shape is not supported by VIPS driver.');
    }
}
