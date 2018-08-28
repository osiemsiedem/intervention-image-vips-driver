<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Interpretation;

class GreyscaleCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        return $this->handleCommand(function () use ($image) {
            $core = $image->getCore();

            $core = $core->colourspace(Interpretation::B_W);

            $image->setCore($core);
        });
    }
}
