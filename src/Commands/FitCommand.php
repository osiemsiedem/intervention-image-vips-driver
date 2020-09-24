<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Size;

class FitCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $width = $this->argument(0)
            ->type('digit')
            ->required()
            ->value();

        $height = $this->argument(1)
            ->type('digit')
            ->value($width);

        $constraints = $this->argument(2)
            ->type('closure')
            ->value();

        $position = $this->argument(3)
            ->type('string')
            ->value('center');

        $size = new Size($width, $height);

        return $this->handleCommand(function () use ($image, $width, $height, $constraints, $position, $size) {
            $sizeBefore = $image->getSize()->fit($size, $position);

            $sizeAfter = (clone $sizeBefore)->resize($width, $height, $constraints);

            $core = $image->getCore();

            $core = $core->crop($sizeBefore->pivot->x, $sizeBefore->pivot->y, $sizeBefore->width, $sizeBefore->height);

            $newWidth = (int) round($sizeAfter->getWidth());
            $newHeight = (int) round($sizeAfter->getHeight());

            $core = $this->resizeImage($core, $newWidth, $newHeight);

            $image->setCore($core);
        });
    }
}
