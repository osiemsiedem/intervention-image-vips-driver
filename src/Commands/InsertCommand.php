<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Image;
use Jcupitt\Vips\Extend;

class InsertCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $source = $this->argument(0)
            ->required()
            ->value();

        $position = $this->argument(1)
            ->type('string')
            ->value();

        $x = $this->argument(2)
            ->type('digit')
            ->value(0);

        $y = $this->argument(3)
            ->type('digit')
            ->value(0);

        return $this->handleCommand(function () use ($image, $source, $position, $x, $y) {
            $watermark = $image->getDriver()->init($source);

            $imageCore = $image->getCore();
            $watermarkCore = $watermark->getCore();

            $imageSize = $image->getSize()->align($position, $x, $y);
            $watermarkSize = $watermark->getSize()->align($position);

            $target = $imageSize->relativePosition($watermarkSize);

            if ($watermarkCore->hasAlpha()) {
                $watermarkCore = $watermarkCore->embed($target->x, $target->y, $imageSize->width, $imageSize->height, [
                    'extend'     => Extend::BACKGROUND,
                    'background' => [0, 0, 0, 0],
                ]);

                $imageCore = $imageCore->composite([$imageCore, $watermarkCore], [BlendMode::OVER]);
            } else {
                $imageCore = $imageCore->insert($watermarkCore->bandjoin(255), $target->x, $target->y);
            }

            $image->setCore($imageCore);
        });
    }
}
