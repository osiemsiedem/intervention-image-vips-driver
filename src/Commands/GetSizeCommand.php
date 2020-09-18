<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Size;
use Jcupitt\Vips\Image;

class GetSizeCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        return $this->handleCommand(function () use ($image) {
            /** @var Image $core */
            $core = $image->getCore();

            $core = $core->autorot();

            $size = new Size($core->width, $core->height);

            $this->setOutput($size);
        });
    }
}
