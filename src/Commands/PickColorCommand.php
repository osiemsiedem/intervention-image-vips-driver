<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Vips\Color;
use Jcupitt\Vips\Image;

class PickColorCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $x = $this->argument(0)
            ->type('digit')
            ->required()
            ->value();

        $y = $this->argument(1)
            ->type('digit')
            ->required()
            ->value();

        $format = $this->argument(2)
            ->type('string')
            ->value('array');

        return $this->handleCommand(function () use ($image, $x, $y, $format) {
            /** @var Image $core */
            $core = $image->getCore();

            $pixel = $core->getpoint($x, $y);

            $color = new Color($pixel);

            $color = $color->format($format);

            $this->setOutput($color);
        });
    }
}
