<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;


use Jcupitt\Vips\Image;

class PixelateCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image)
    {
        $size = $this->argument(0)->type('digit')->value(10);

        return $this->handleCommand(
            function () use($image, $size) {
                /** @var Image $core */
                $core = $image->getCore();

                $core = $core->resize(1/$size)->resize($size);

                $image->setCore($core);
            }
        );
    }
}
