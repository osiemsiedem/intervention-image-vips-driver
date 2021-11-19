<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Shapes;

use Intervention\Image\AbstractShape;
use Intervention\Image\Exception\NotSupportedException;
use Intervention\Image\Image;

class RectangleShape extends AbstractShape
{
    /**
     * @var int
     */
    public $x1 = 0;

    /**
     * @var int
     */
    public $y1 = 0;

    /**
     * @var int
     */
    public $x2 = 0;

    /**
     * @var int
     */
    public $y2 = 0;

    /**
     * Create a new shape instance.
     *
     * @param  int|null  $x1
     * @param  int|null  $y1
     * @param  int|null  $x2
     * @param  int|null  $y2
     * @return void
     */
    public function __construct($x1 = null, $y1 = null, $x2 = null, $y2 = null)
    {
        $this->x1 = is_numeric($x1) ? (int) $x1 : $this->x1;
        $this->y1 = is_numeric($y1) ? (int) $y1 : $this->y1;
        $this->x2 = is_numeric($x2) ? (int) $x2 : $this->x2;
        $this->y2 = is_numeric($y2) ? (int) $y2 : $this->y2;
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
        throw new NotSupportedException('Rectangle shape is not supported by VIPS driver.');
    }
}
