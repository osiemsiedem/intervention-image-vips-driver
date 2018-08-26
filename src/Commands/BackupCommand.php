<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Intervention\Image\Commands\AbstractCommand;

class BackupCommand extends AbstractCommand
{
    /**
     * Execute the command.
     *
     * @param  \Intervention\Image\Image  $image
     * @return bool
     */
    public function execute($image): bool
    {
        $name = $this->argument(0)->value();

        $clone = clone $image;

        $image->setBackup($clone->getCore(), $name);

        return true;
    }
}
