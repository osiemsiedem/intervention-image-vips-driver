<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Exception;
use Intervention\Image\Commands\AbstractCommand;

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
        try {
            $core = $image->getCore();

            if ($core->hasalpha()) {
                // https://github.com/jcupitt/libvips/issues/59#issuecomment-222351004
                $flatten = $core->extract_band(0, ['n' => $core->bands - 1]);

                $mask = $core->extract_band($core->bands - 1, ['n' => 1]);

                $core = $flatten->invert()->bandjoin($mask);
            } else {
                $core = $core->invert();
            }

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
