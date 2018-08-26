<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Exception;
use Intervention\Image\Commands\AbstractCommand;

class InvertCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        try {
            $core = $image->getCore();

            $core = $core->invert();

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
