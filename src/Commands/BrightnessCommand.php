<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

class BrightnessCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $level = $this->argument(0)
            ->between(-100, 100)
            ->required()
            ->value() * 2.55;

        return $this->handleCommand(function () use ($image, $level) {
            $core = $image->getCore();

            if ($core->hasAlpha()) {
                $flatten = $this->flattenImage($core);

                $mask = $this->extractAlphaChannel($core);

                $core = $flatten->linear([1, 1, 1], [$level, $level, $level])->bandjoin($mask);
            } else {
                $core = $core->linear([1, 1, 1], [$level, $level, $level]);
            }

            $image->setCore($core);
        });
    }
}
