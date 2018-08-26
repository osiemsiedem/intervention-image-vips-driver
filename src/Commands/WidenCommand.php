<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Commands\AbstractCommand;
use Intervention\Image\Exception\NotSupportedException;

class WidenCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return void
     * @throws \Intervention\Image\Exception\NotSupportedException
     */
    public function execute($image): void
    {
        throw new NotSupportedException('Widen command is not supported by VIPS driver.');
    }
}
