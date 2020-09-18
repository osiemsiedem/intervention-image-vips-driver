<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Vips\Color;
use Jcupitt\Vips\BlendMode;
use Jcupitt\Vips\Image;

class LimitColorsCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image)
    {
        $count = $this->argument(0)->type('int')->value();
        $matte = $this->argument(1)->value();

        $bits = 8;
        if ($count < 3) {
            $bits = 1;
        } elseif ($count < 5) {
            $bits = 2;
        } elseif ($count < 17) {
            $bits = 4;
        }

        return $this->handleCommand(
            function () use ($image, $bits, $matte) {
                /** @var Image $core */
                $core = $image->getCore();

                $alpha = null;
                if($core->hasAlpha()) {
                    $alpha = $this->extractAlphaChannel($core);
                    $core = $this->flattenImage($core);
                }

                if($matte) {
                    $matteColor = new Color($matte);

                    $canvas = $image->getDriver()->newImage(
                        $core->width,
                        $core->height,
                        $matteColor->getRgba()
                    );

                    $buffer = $core->pngsave_buffer(
                        [
                            'palette' => true,
                            'bitdepth' => $bits,
                            'dither' => 0.5,
                            'Q' => 90,
                        ]
                    );
                    $core = Image::pngload_buffer($buffer);
                    if($alpha) {
                        $core = $core->bandjoin($alpha);
                    }

                    $canvas = $canvas->getCore()->composite2($core, BlendMode::OVER);

                    $image->setCore($canvas);
                } else {
                    $buffer = $core->pngsave_buffer(
                        [
                            'palette' => true,
                            'bitdepth' => $bits,
                            'dither' => 0.5,
                            'Q' => 90,
                        ]
                    );
                    $core = Image::pngload_buffer($buffer);
                    if ($alpha) {
                        $core = $core->bandjoin($alpha);
                    }

                    $image->setCore($core);
                }
            }
        );
    }
}
