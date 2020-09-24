<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Exception\InvalidArgumentException;
use Jcupitt\Vips\Image;
use Jcupitt\Vips\Interesting;

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

        return $this->handleCommand(function () use ($image, $width, $height, $x, $y) {
            /** @var Image $core */
            $core = $image->getCore();

            if (is_null($x) || is_null($y)) {
                $core = $core->smartcrop($width, $height, ['interesting' => Interesting::CENTRE]);
            } else {
                $core = $core->crop($x, $y, $width, $height);
            }

            $image->setCore($core);
        });
    }
}
