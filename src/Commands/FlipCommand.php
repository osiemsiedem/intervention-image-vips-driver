<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Jcupitt\Vips\Direction;
use Jcupitt\Vips\Image;

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
        $mode = strtolower((string) $this->argument(0)->value('h'));

        return $this->handleCommand(function () use ($image, $mode) {
            /** @var Image $core */
            $core = $image->getCore();

            if (in_array($mode, ['2', 'v', 'vert', 'vertical'])) {
                $core = $core->flip(Direction::VERTICAL);
            } else {
                $core = $core->flip(Direction::HORIZONTAL);
            }

            $image->setCore($core);
        });
    }
}
