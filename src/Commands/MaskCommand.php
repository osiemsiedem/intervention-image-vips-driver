<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\BlendMode;
use Jcupitt\Vips\Image;

class MaskCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image)
    {
        $mask_source = $this->argument(0)->value();
        $mask_w_alpha = $this->argument(1)
                             ->type('bool')
                             ->value(false);


        return $this->handleCommand(
            function () use($image, $mask_source, $mask_w_alpha) {
                $mask = $image->getDriver()->init($mask_source);
                if(
                    $mask->width() !== $image->width() ||
                    $mask->height() !== $image->height()
                ) {
                    $mask->resize($image->width(), $image->height());
                }
                $mask = $mask->getCore();

                if($mask_w_alpha) {
                    $mask = $this->extractAlphaChannel($mask);
                } else {
                    $mask = $mask->bandmean();
                }

                /** @var Image $core */
                $core = $image->getCore();
                if($core->hasAlpha()) {
                    $mask = $this->extractAlphaChannel($core)
                                 ->composite2($mask, BlendMode::DARKEN);
                    $core = $this->flattenImage($core);
                }

                $core = $core->bandjoin($mask);

                $image->setCore($core);
            }
        );
    }
}
