<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Exception;
use Intervention\Image\Commands\AbstractCommand;

class GammaCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $gamma = $this->argument(0)
            ->type('numeric')
            ->required()
            ->value();

        try {
            $core = $image->getCore();

            $core = $core->gamma(['exponent' => $gamma]);

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
