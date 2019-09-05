<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

class InvertCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        return $this->handleCommand(function () use ($image) {
            $core = $image->getCore();

            if ($core->hasAlpha()) {
                $flatten = $this->flattenImage($core);

                $mask = $this->extractAlphaChannel($core);

                $core = $flatten->invert()->bandjoin($mask);
            } else {
                $core = $core->invert();
            }

            $image->setCore($core);
        });
    }
}
