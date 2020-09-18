<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Image;

class ResizeCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $width = $this->argument(0)->value();

        $height = $this->argument(1)->value();

        $constraints = $this->argument(2)
            ->type('closure')
            ->value();

        return $this->handleCommand(function () use ($image, $width, $height, $constraints) {
            /** @var Image $core */
            $core = $image->getCore();

            $size = $image->getSize()->resize($width, $height, $constraints);

            $newWidth = (int) round($size->getWidth());
            $newHeight = (int) round($size->getHeight());

            $core = $this->resizeImage($core, $newWidth, $newHeight);

            $image->setCore($core);
        });
    }
}
