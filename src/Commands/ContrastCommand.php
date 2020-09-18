<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;


use Jcupitt\Vips\Image;

class ContrastCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image)
    {
        $level = $this->argument(0)->between(-100, 100)->required()->value();

        return $this->handleCommand(
            function () use($image, $level) {
                /** @var Image $core */
                $core = $image->getCore();

                // calculate a and b for linear
                $a = 1 + $level / 100;
                $b = 255 * (1 - $a);

                if($core->hasAlpha()) {
                    $flatten = $this->flattenImage($core);

                    $mask = $this->extractAlphaChannel($core);

                    $core = $flatten->linear([$a, $a, $a], [$b, $b, $b])
                                    ->bandjoin($mask);
                } else {
                    $core = $core->linear([$a, $a, $a], [$b, $b, $b]);
                }

                $image->setCore($core);
            }
        );
    }
}
