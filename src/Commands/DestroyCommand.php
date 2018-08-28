<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

class DestroyCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        return true;
    }
}
