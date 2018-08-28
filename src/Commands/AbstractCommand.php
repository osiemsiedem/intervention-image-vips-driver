<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Closure;
use Jcupitt\Vips\Image;
use Jcupitt\Vips\Exception;
use Intervention\Image\Commands\AbstractCommand as BaseAbstractCommand;

abstract class AbstractCommand extends BaseAbstractCommand
{
    /**
     * Flatten the image.
     *
     * @param  \Jcupitt\Vips\Image  $image
     * @return bool
     * @link   https://github.com/jcupitt/libvips/issues/59#issuecomment-222351004
     */
    protected function flattenImage(Image $image): Image
    {
        return $image->extract_band(0, ['n' => $image->bands - 1]);
    }

    /**
     * Extract the alpha channel.
     *
     * @param  \Jcupitt\Vips\Image  $image
     * @return bool
     * @link   https://github.com/jcupitt/libvips/issues/59#issuecomment-222351004
     */
    protected function extractAlphaChannel(Image $image): Image
    {
        return $image->extract_band($image->bands - 1, ['n' => 1]);
    }

    /**
     * Handle the command.
     *
     * @param  \Closure  $command
     * @return bool
     */
    protected function handleCommand(Closure $command): bool
    {
        try {
            $command();
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
