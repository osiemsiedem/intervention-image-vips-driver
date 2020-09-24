<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Shapes;

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
     */
    public function applyToImage(Image $image, $x = 0, $y = 0)
    {
        $this->applyToImageViaSVG(
            $image,
            'polygon',
            [
                'points' => implode(',', $this->points),
            ] + $this->getSVGAttributes()
        );
    }
}
