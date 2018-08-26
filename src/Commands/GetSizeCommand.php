<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Exception;
use Intervention\Image\Size;
use Intervention\Image\Commands\AbstractCommand;

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
        try {
            $core = $image->getCore();

            $size = new Size($core->width, $core->height);

            $this->setOutput($size);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
