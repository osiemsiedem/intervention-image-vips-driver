<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;


use Intervention\Image\Vips\Color;
use Jcupitt\Vips\Extend;
use Jcupitt\Vips\Image;

class ResizeCanvasCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image)
    {
        $width = $this->argument(0)->type('digit')->required()->value();
        $height = $this->argument(1)->type('digit')->required()->value();
        $anchor = $this->argument(2)->value('center');
        $relative = $this->argument(3)->type('boolean')->value(false);
        $bgcolor = $this->argument(4)->value('#ffffff');

        $original_width = $image->getWidth();
        $original_height = $image->getHeight();

        // check of only width or height is set
        $width = is_null($width) ? $original_width : (int) $width;
        $height = is_null($height) ? $original_height : (int) $height;

        // check on relative width/height
        if ($relative) {
            $width = $original_width + $width;
            $height = $original_height + $height;
        }

        // check for negative width/height
        $width = ($width <= 0) ? $width + $original_width : $width;
        $height = ($height <= 0) ? $height + $original_height : $height;

        return $this->handleCommand(
            function () use($image, $width, $height, $anchor, $bgcolor) {

                /** @var Image $core */
                $core = $image->getCore();

                $anchor_to_gravity = [
                    'top-left' => 'north-west',
                    'top' => 'north',
                    'top-right' => 'north-east',
                    'left' => 'west',
                    'center' => 'centre',
                    'right' => 'east',
                    'bottom-left' => 'south-west',
                    'bottom' => 'south',
                    'bottom-right' => 'south-east'
                ];

                $color = new Color($bgcolor);

                if(!$core->hasAlpha()) {
                    $core = $core->bandjoin_const(255);
                }
                $core = $core->gravity(
                    $anchor_to_gravity[$anchor],
                    $width,
                    $height,
                    [
                        'extend'     => Extend::BACKGROUND,
                        'background' => [$color->red, $color->green, $color->blue, $color->alpha],
                    ]
                );

                $image->setCore($core);
            }
        );
    }
}
