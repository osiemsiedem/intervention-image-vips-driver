<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Shapes;

use Intervention\Image\Image;
use Intervention\Image\Vips\Color;

class LineShape extends AbstractShape
{
    /**
     * @var int
     */
    public $x = 0;

    /**
     * @var int
     */
    public $y = 0;

    /**
     * @var string
     */
    public $color = '#000000';

    /**
     * @var int
     */
    public $width = 1;

    /**
     * Create a new shape instance.
     *
     * @param  int|null  $x
     * @param  int|null  $y
     * @return void
     */
    public function __construct($x = null, $y = null)
    {
        $this->x = is_numeric($x) ? (int) $x : $this->x;
        $this->y = is_numeric($y) ? (int) $y : $this->y;
    }

    /**
     * Set the line color.
     *
     * @param  string  $color
     * @return void
     */
    public function color(string $color)
    {
        $this->color = $color;
    }

    /**
     * Set the line width.
     *
     * @param  int  $width
     * @return void
     */
    public function width(int $width)
    {
        $this->width = $width;
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
            'line',
            [
                'x1' => $this->x,
                'y1' => $this->y,
                'x2' => $x,
                'y2' => $y,
                'stroke-width' => $this->width,
                'stroke' => (new Color($this->color))->getRgba(),
            ]
        );
    }
}
