<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Vips\Color;
use Jcupitt\Vips\Image;

class RotateCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $angle = -$this->argument(0)
            ->type('numeric')
            ->required()
            ->value() % 360;

        $color = new Color($this->argument(1)->value());

        return $this->handleCommand(function () use ($image, $angle, $color) {
            /** @var Image $core */
            $core = $image->getCore();

            switch ($angle) {
                case 0:
                    break;

                case 90:
                case -270:
                    $core = $core->rot90();
                    break;

                case 180:
                case -180:
                    $core = $core->rot180();
                    break;

                case -90:
                case 270:
                    $core = $core->rot270();
                    break;

                default:
                    if ($color->alpha > 0 && $color->alpha < 255) {
                        $background = [$color->red, $color->green, $color->blue, $color->alpha];

                        if (! $core->hasAlpha()) {
                            $core = $core->bandjoin_const(255);
                        }
                    } else {
                        $background = [$color->red, $color->green, $color->blue];
                    }

                    $core = $core->similarity([
                        'background' => $background,
                        'angle'      => $angle,
                    ]);
                    break;
            }

            $image->setCore($core);
        });
    }
}
