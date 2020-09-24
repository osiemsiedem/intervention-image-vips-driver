<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Image;

class TrimCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image)
    {
        $base = $this->argument(0)
                     ->type('string')
                     ->value('top-left');
        $away = $this->argument(1)
                     ->value();
        $tolerance = $this->argument(2)
                          ->type('numeric')
                          ->value(0);
        $feather = $this->argument(3)
                        ->type('numeric')
                        ->value(0);

        $width = $image->getWidth();
        $height = $image->getHeight();

        $checkTransparency = false;

        // define borders to trim away
        if (is_null($away)) {
            $away = ['top', 'right', 'bottom', 'left'];
        } elseif (is_string($away)) {
            $away = [$away];
        }

        // lower border names
        foreach ($away as $key => $value) {
            $away[$key] = strtolower($value);
        }

        // define base color position
        switch (strtolower($base)) {
            case 'transparent':
            case 'trans':
                $checkTransparency = true;
                $base_x = 0;
                $base_y = 0;
                break;

            case 'bottom-right':
            case 'right-bottom':
                $base_x = $width - 1;
                $base_y = $height - 1;
                break;

            default:
            case 'top-left':
            case 'left-top':
                $base_x = 0;
                $base_y = 0;
                break;
        }

        return $this->handleCommand(
            function () use ($image, $base_x, $base_y, $checkTransparency, $away, $tolerance, $feather) {
                /** @var Image $core */
                $core = $image->getCore();

                $trim_core = $core;

                if ($checkTransparency) {
                    $point = [0, 0, 0];
                    $trim_core = $this->extractAlphaChannel($core);
                } else {
                    $point = $core->getpoint($base_x, $base_y);
                    unset($point[3]);
                }
                $trim = $trim_core->find_trim(
                    [
                        'background' => $point,
                        'threshold' => $tolerance,
                    ]
                );

                $crop_x = $trim['left'];
                $crop_y = $trim['top'];
                $crop_width = $trim['width'];
                $crop_height = $trim['height'];

                if (! in_array('right', $away, true)) {
                    $crop_width = $crop_width + ($image->width() - $crop_width - $crop_x);
                }

                if (! in_array('bottom', $away, true)) {
                    $crop_height = $crop_height + ($image->height() - $crop_height - $crop_y);
                }

                if (! in_array('left', $away, true)) {
                    $crop_width += $crop_x;
                    $crop_x = 0;
                }

                if (! in_array('top', $away, true)) {
                    $crop_height += $crop_y;
                    $crop_y = 0;
                }

                // add feather
                $crop_width = min($image->width(), ($crop_width + $feather * 2));
                $crop_height = min($image->height(), ($crop_height + $feather * 2));
                $crop_x = max(0, ($crop_x - $feather));
                $crop_y = max(0, ($crop_y - $feather));

                // crop image
                $core = $core->crop($crop_x, $crop_y, $crop_width, $crop_height);
                $image->setCore($core);
            }
        );
    }
}
