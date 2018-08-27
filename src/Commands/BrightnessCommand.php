<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Exception;
use Intervention\Image\Commands\AbstractCommand;

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

        try {
            $core = $image->getCore();

            if ($core->hasalpha()) {
                // https://github.com/jcupitt/libvips/issues/59#issuecomment-222351004
                $flatten = $core->extract_band(0, ['n' => $core->bands - 1]);

                $mask = $core->extract_band($core->bands - 1, ['n' => 1]);

                $core = $flatten->linear([1, 1, 1], [$level, $level, $level])->bandjoin($mask);
            } else {
                $core = $core->linear([1, 1, 1], [$level, $level, $level]);
            }

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
