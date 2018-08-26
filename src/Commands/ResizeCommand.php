<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Exception;
use Intervention\Image\Commands\AbstractCommand;

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

        try {
            $core = $image->getCore();

            $size = $image->getSize()->resize($width, $height, $constraints);

            $core = $core->resize($size->getWidth() / $core->width, ['vscale' => $size->getHeight() / $core->height]);

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
