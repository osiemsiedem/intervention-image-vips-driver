<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Image;
use Jcupitt\Vips\Exception;
use Intervention\Image\Size;
use Intervention\Image\Point;
use Intervention\Image\Commands\AbstractCommand;
use Intervention\Image\Exception\InvalidArgumentException;

class CropCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     * @throws \Intervention\Image\Exception\InvalidArgumentException
     */
    public function execute($image): bool
    {
        $width = $this->argument(0)
            ->type('digit')
            ->required()
            ->value();

        $height = $this->argument(1)
            ->type('digit')
            ->required()
            ->value();

        if (is_null($width) || is_null($height)) {
            throw new InvalidArgumentException('Width and height of cutout needs to be defined.');
        }

        $x = $this->argument(2)
            ->type('digit')
            ->value();

        $y = $this->argument(3)
            ->type('digit')
            ->value();

        $size = new Size($width, $height);

        $position = new Point($x, $y);

        try {
            if (is_null($x) && is_null($y)) {
                $position = $image
                    ->getSize()
                    ->align('center')
                    ->relativePosition($size->align('center'));
            }

            $core = $image->getCore();

            $core = $core->crop($position->x, $position->y, $size->width, $size->height);

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
