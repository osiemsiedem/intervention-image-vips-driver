<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Extend;
use Jcupitt\Vips\BlendMode;
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
            /** @var \Jcupitt\Vips\Image $core */
            $core = $image->getCore();

            try {
                $filling = $image->getDriver()->decoder->init($filling);
            } catch (NotReadableException $e) {
                $filling = new Color($filling);
            }

            if ($filling instanceof Image) {
                $overlayCore = $filling->getCore();

                $overlayCore = $overlayCore->embed(
                    0,
                    0,
                    $core->width,
                    $core->height,
                    [
                        'extend'     => Extend::REPEAT,
                    ]
                );
            } elseif ($filling instanceof Color) {
                $overlay = $image->getDriver()->newImage(
                    $core->width,
                    $core->height,
                    $filling->getRgba()
                );
                $overlayCore = $overlay->getCore();
            } else {
                return;
            }

            if (is_int($x) && is_int($y)) {
                $mask = \Jcupitt\Vips\Image::black($core->width, $core->height);
                $mask = $mask->draw_flood(
                    [255],
                    $x,
                    $y,
                    [
                        'equal' => true,
                        'test' => $core
                    ]
                );

                if ($overlayCore->hasAlpha()) {
                    $mask = $mask->composite2(
                        $this->extractAlphaChannel($overlayCore),
                        BlendMode::DARKEN
                    );
                    $overlayCore = $this->flattenImage($overlayCore);
                }
                $overlayCore = $overlayCore->bandjoin($mask[0]);
            }
            $core = $core->composite2($overlayCore, BlendMode::OVER);

            $image->setCore($core);
        });
    }
}
