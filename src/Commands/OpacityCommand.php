<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Exception;
use Intervention\Image\Vips\Driver;
use Intervention\Image\Commands\AbstractCommand;

class OpacityCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $transparency = $this->argument(0)
            ->between(0, 100)
            ->required()
            ->value() / 100;

        try {
            $core = $image->getCore();

            if ( ! $core->hasalpha()) {
                $background = (new Driver)->newImage($core->width, $core->height, [0, 0, 0, 0])->getCore();

                $core = $background->composite([$background, $core], 2);
            }

            $core = $core->multiply([1.0, 1.0, 1.0, $transparency]);

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
