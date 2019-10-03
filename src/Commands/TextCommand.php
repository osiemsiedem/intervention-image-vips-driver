<?php

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Exception\NotSupportedException;

class TextCommand extends AbstractCommand
{
    /**
     * @param \Intervention\Image\Image $image
     *
     * @return mixed
     */
    public function execute($image)
    {
        throw new NotSupportedException('Text command is not supported by VIPS driver.');
    }
}
