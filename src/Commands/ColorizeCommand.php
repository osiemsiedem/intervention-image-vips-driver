<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

class ColorizeCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image)
    {
        $red = $this->argument(0)
                    ->between(-100, 100)
                    ->required()
                    ->value();
        $green = $this->argument(1)
                      ->between(-100, 100)
                      ->required()
                      ->value();
        $blue = $this->argument(2)
                     ->between(-100, 100)
                     ->required()
                     ->value();

        return $this->handleCommand(
            function () use($image, $red, $green, $blue) {
                $core = $image->getCore();

                // calculate a and b for colors linear transformation
                $a = [1, 1, 1];
                $b = [0, 0, 0];
                [$a[0], $b[0]] = $this->normalizeLevel($red);
                [$a[1], $b[1]] = $this->normalizeLevel($green);
                [$a[2], $b[2]] = $this->normalizeLevel($blue);

                if($core->hasAlpha()) {
                    $flatten = $this->flattenImage($core);

                    $mask = $this->extractAlphaChannel($core);

                    $core = $flatten->linear($a, $b)
                                    ->bandjoin($mask);
                } else {
                    $core = $core->linear($a, $b);
                }

                $image->setCore($core);
            }
        );
    }

    private function normalizeLevel($level) {
        if($level > 0) {
            return [1 - $level / 100, $level * 2.55];
        }
        return [1 + $level / 100, 0];
    }
}
