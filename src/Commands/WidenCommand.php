<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

class WidenCommand extends ResizeCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $width = $this->argument(0)
            ->type('digit')
            ->required()
            ->value();

        $constraints = $this->argument(1)
            ->type('closure')
            ->value();

        $this->arguments[0] = $width;

        $this->arguments[1] = null;

        $this->arguments[2] = function ($constraint) use ($constraints) {
            $constraint->aspectRatio();

            if (is_callable($constraints)) {
                $constraints($constraint);
            }
        };

        return parent::execute($image);
    }
}
