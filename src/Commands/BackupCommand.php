<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

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

        return $this->handleCommand(function () use ($image, $name) {
            $clone = clone $image;

            $image->setBackup($clone->getCore(), $name);
        });
    }
}
