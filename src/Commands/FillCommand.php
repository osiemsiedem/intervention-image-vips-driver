<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Extend;
use Intervention\Image\Image;
use Intervention\Image\Vips\Color;
use Intervention\Image\Exception\NotReadableException;

class FillCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $filling = $this->argument(0)->value();

        $x = $this->argument(1)
            ->type('digit')
            ->value();

        $y = $this->argument(2)
            ->type('digit')
            ->value();

        return $this->handleCommand(function () use ($image, $filling, $x, $y) {
            $core = $image->getCore();

            try {
                $filling = $image->getDriver()->decoder->init($filling);
            } catch (NotReadableException $e) {
                $filling = new Color($filling);
            }

            if (is_int($x) && is_int($y)) {
                if ($filling instanceof Image) {
                    //
                } elseif ($filling instanceof Color) {
                    //
                }
            } else {
                if ($filling instanceof Image) {
                    $overlayCore = $filling->getCore();

                    $overlayCore = $overlayCore->embed(0, 0, $core->width, $core->height, [
                        'extend'     => Extend::REPEAT,
                        'background' => [0, 0, 0, 0],
                    ]);

                    $core = $core->composite([$core, $overlayCore], 2);
                } elseif ($filling instanceof Color) {
                    $overlay = $image->getDriver()->newImage($core->width, $core->height, $filling->getArray());

                    $overlayCore = $overlay->getCore();

                    $core = $core->composite([$core, $overlayCore], 2);
                }
            }

            $image->setCore($core);
        });
    }
}
