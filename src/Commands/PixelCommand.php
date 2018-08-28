<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Vips\Color;

class PixelCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $color = $this->argument(0)
            ->required()
            ->value();

        $color = new Color($color);

        $x = $this->argument(1)
            ->type('digit')
            ->required()
            ->value();

        $y = $this->argument(2)
            ->type('digit')
            ->required()
            ->value();

        return $this->handleCommand(function () use ($image, $color, $x, $y) {
            $core = $image->getCore();

            $core = $core->draw_rect([$color->red, $color->green, $color->blue, $color->alpha], $x, $y, 1, 1);

            $image->setCore($core);
        });
    }
}
