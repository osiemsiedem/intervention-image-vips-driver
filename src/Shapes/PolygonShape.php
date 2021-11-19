<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Shapes;

use Intervention\Image\AbstractShape;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Image;

class PolygonShape extends AbstractShape
{
    /**
     * @var array
     */
    public $points;

    /**
     * Create a new shape instance.
     *
     * @param  array  $points
     * @return void
     */
    public function __construct(array $points)
    {
        $this->points = $points;
    }

    /**
     * Draw the shape.
     *
     * @param  \Intervention\Image\Image  $image
     * @param  int  $x
     * @param  int  $y
     * @return void
     *
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        throw new NotSupportedException('Polygon shape is not supported by VIPS driver.');
    }
}
