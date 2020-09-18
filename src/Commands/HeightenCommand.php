<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

class HeightenCommand extends ResizeCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $height = $this->argument(0)
            ->type('digit')
            ->required()
            ->value();

        $constraints = $this->argument(1)
            ->type('closure')
            ->value();

        $this->arguments[0] = null;

        $this->arguments[1] = $height;

        $this->arguments[2] = static function ($constraint) use ($constraints) {
            $constraint->aspectRatio();

            if (is_callable($constraints)) {
                $constraints($constraint);
            }
        };

        return parent::execute($image);
    }
}
