<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Image;
use Jcupitt\Vips\Exception;
use Intervention\Image\Size;
use Intervention\Image\Commands\AbstractCommand;

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

        try {
            $sizeBefore = $image->getSize()->fit($size, $position);

            $sizeAfter = (clone $sizeBefore)->resize($width, $height, $constraints);

            $core = $image->getCore();

            $core = $core->crop($sizeBefore->pivot->x, $sizeBefore->pivot->y, $sizeBefore->width, $sizeBefore->height);

            $core = $core->resize($sizeAfter->getWidth() / $core->width, ['vscale' => $sizeAfter->getHeight() / $core->height]);

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
