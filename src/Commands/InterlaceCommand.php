<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Vips\Encoder;

class InterlaceCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return void
     */
    public function execute($image)
    {
        $mode = $this->argument(0)
                     ->type('bool')
                     ->value(true);

        /** @var Encoder $encoder */
        $encoder = $image->getDriver()->encoder;
        $encoder->interlace = $mode;
    }
}
