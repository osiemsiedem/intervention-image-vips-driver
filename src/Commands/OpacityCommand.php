<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\BlendMode;
use Jcupitt\Vips\Image;

class OpacityCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $transparency = $this->argument(0)
            ->between(0, 100)
            ->required()
            ->value() / 100;

        return $this->handleCommand(function () use ($image, $transparency) {
            /** @var Image $core */
            $core = $image->getCore();

            if (! $core->hasAlpha()) {
                $core = $core->bandjoin_const(255);
            }

            $core = $core->multiply([1.0, 1.0, 1.0, $transparency]);

            $image->setCore($core);
        });
    }
}
