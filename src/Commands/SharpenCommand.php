<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Image;
use Jcupitt\Vips\Exception;
use Intervention\Image\Commands\AbstractCommand;

class SharpenCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $amount = $this->argument(0)
            ->between(0, 100)
            ->value(10);

        $min = $amount >= 10 ? $amount * -0.01 : 0;
        $max = $amount * -0.025;
        $abs = ((4 * $min + 4 * $max) * -1) + 1;

        try {
            $mask = Image::newFromArray([
                [$min, $max, $min],
                [$max, $abs, $max],
                [$min, $max, $min],
            ], 1, 0);

            $core = $image->getCore();

            $core = $core->conv($mask);

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
