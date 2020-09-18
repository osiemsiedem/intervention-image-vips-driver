<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Commands;

use Closure;
use Intervention\Image\Commands\AbstractCommand as BaseAbstractCommand;
use Jcupitt\Vips\Exception;
use Jcupitt\Vips\Image;

abstract class AbstractCommand extends BaseAbstractCommand
{
    /**
     * Flatten the image.
     *
     * @param  \Jcupitt\Vips\Image  $image
     * @return \Jcupitt\Vips\Image
     * @link   https://github.com/jcupitt/libvips/issues/59#issuecomment-222351004
     */
    protected function flattenImage(Image $image): Image
    {
        return $image->extract_band(0, ['n' => $image->bands - 1]);
    }

    /**
     * Resize the image.
     *
     * @param  \Jcupitt\Vips\Image  $image
     * @param  int  $width
     * @param  int  $height
     * @return \Jcupitt\Vips\Image
     */
    protected function resizeImage(Image $image, int $width, int $height): Image
    {
        $options = [
            'height' => $height,
            'size'   => 'force',
        ];

        if ($image->typeof('icc-profile-data') !== 0) {
            $options['import_profile'] = __DIR__.'/../../icc/sRGB2014.icc';
            $options['export_profile'] = __DIR__.'/../../icc/sRGB2014.icc';
        }

        return $image->thumbnail_image($width, $options);
    }

    /**
     * Extract the alpha channel.
     *
     * @param  \Jcupitt\Vips\Image  $image
     * @return \Jcupitt\Vips\Image
     * @link   https://github.com/jcupitt/libvips/issues/59#issuecomment-222351004
     */
    protected function extractAlphaChannel(Image $image): Image
    {
        return $image->extract_band($image->bands - 1, ['n' => 1]);
    }

    /**
     * Handle the command.
     *
     * @param  Closure  $command
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
