<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Exception;
use Intervention\Image\Vips\Color;
use Intervention\Image\Commands\AbstractCommand;

class PickColorCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $x = $this->argument(0)
            ->type('digit')
            ->required()
            ->value();

        $y = $this->argument(1)
            ->type('digit')
            ->required()
            ->value();

        $format = $this->argument(2)
            ->type('string')
            ->value('array');

        try {
            $core = $image->getCore();

            $pixel = $core->getpoint($x, $y);

            $color = new Color($pixel);

            $color = $color->format($format);

            $this->setOutput($color);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
