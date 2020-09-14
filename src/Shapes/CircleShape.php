<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Shapes;

class CircleShape extends EllipseShape
{
    /**
     * Create a new shape instance.
     *
     * @param  int|null  $diameter
     * @return void
     */
    public function __construct($diameter = null)
    {
        parent::__construct($diameter, $diameter);
    }
}
