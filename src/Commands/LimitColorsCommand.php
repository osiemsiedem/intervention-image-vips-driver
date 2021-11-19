<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Exception\NotSupportedException;

class LimitColorsCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return void
     *
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function execute($image)
    {
        throw new NotSupportedException('LimitColors command is not supported by VIPS driver.');
    }
}
