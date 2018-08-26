<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Direction;
use Jcupitt\Vips\Exception;
use Intervention\Image\Commands\AbstractCommand;

class FlipCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $mode = $this->argument(0)->value('h');

        try {
            $core = $image->getCore();

            if (in_array(strtolower($mode), [2, 'v', 'vert', 'vertical'])) {
                $core = $core->flip(Direction::VERTICAL);
            } else {
                $core = $core->flip(Direction::HORIZONTAL);
            }

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
