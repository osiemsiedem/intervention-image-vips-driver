<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

class ResetCommand extends AbstractCommand
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

        $backup = clone $image->getBackup($name);

        $image->setCore($backup);

        return true;
    }
}
