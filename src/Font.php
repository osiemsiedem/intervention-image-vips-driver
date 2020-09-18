<?php
declare(strict_types=1);

namespace Intervention\Image\Vips;

use Intervention\Image\AbstractFont;
use Intervention\Image\Exception\RuntimeException;
use Intervention\Image\Image;
use Jcupitt\Vips\Align;
use Jcupitt\Vips\BandFormat;
use Jcupitt\Vips\BlendMode;
use Jcupitt\Vips\Exception;
use Jcupitt\Vips\Extend;
use Jcupitt\Vips\Image as ImageCore;
use Jcupitt\Vips\Interpretation;

class Font extends AbstractFont
{

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function applyToImage(Image $image, $posx = 0, $posy = 0)
    {
        $core = $this->createTextImage();

        $core = $core->cast(BandFormat::UCHAR)
                     ->copy(['interpretation' => Interpretation::SRGB])
                     ->bandjoin([$core, $core, $core]);

        $color = new Color($this->color);

        $core = $core->multiply(
            [
                $color->red/255,
                $color->green/255,
                $color->blue/255,
                $color->alpha/255,
            ]
        );

        $align = is_null($this->align) ? 'left' : strtolower($this->align);
        $valign = is_null($this->valign) ? 'bottom' : strtolower($this->valign);

        // corrections on y-position
        switch ($valign) {
            case 'center':
            case 'middle':
                $posx -= round($core->width / 2);
                break;

            case 'right':
                $posx -= $core->width;
                break;
        }

        // corrections on y-position
        switch ($align) {
            case 'center':
            case 'middle':
                $posy -= round($core->height / 2);
                break;

            case 'bottom':
                $posy -= $core->height;
                break;
        }

        /** @var ImageCore $imageCore */
        $imageCore = $image->getCore();

        if(!$imageCore->hasAlpha()) {
            $imageCore = $imageCore->bandjoin_const(255);
        }

        $core = $core->embed(
            $posx,
            $posy,
            $imageCore->width,
            $imageCore->height,
            [
                'extend' => Extend::BACKGROUND,
                'background' => [0, 0, 0, 0],
            ]
        );

        $imageCore = $imageCore->composite2($core, BlendMode::OVER);

        $image->setCore($imageCore);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function getBoxSize()
    {
        $box = [];

        if($this->text === '') {
            // no text -> no boxsize
            $box['width'] = 0;
            $box['height'] = 0;
        } else {
            $core = $this->createTextImage();
            $box['width'] = $core->width;
            $box['height'] = $core->height;
        }

        return $box;
    }

    /**
     * @return ImageCore
     * @throws Exception
     */
    private function createTextImage() {
        if ($this->hasApplicableFontFile()) {
            $info = pathinfo($this->file);
            $font = basename($this->file, '.'.$info['extension']);
        } else {
            throw new RuntimeException(
                "Font file must be provided to apply text to image."
            );
        }
        $align_to_vips = [
            'left' => Align::LOW,
            'right' => Align::CENTRE,
            'center' => Align::HIGH,
        ];
        $core = ImageCore::text(
            $this->text,
            [
                'font' => $font.' '.$this->size,
                'fontfile' => $this->file,
                'align' => $align_to_vips[$this->align ?: 'left'],
            ]
        );

        switch($this->angle % 360) {
            case 0:
                break;

            case 90:
            case -270:
                $core = $core->rot90();
                break;

            case 180:
            case -180:
                $core = $core->rot180();
                break;

            case -90:
            case 270:
                $core = $core->rot270();
                break;

            default:
                $core = $core->similarity([
                    'background' => [0],
                    'angle' => $this->angle % 360,
                ]);
        }

        return $core;
    }
}
