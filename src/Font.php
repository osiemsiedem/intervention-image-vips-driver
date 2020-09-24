<?php

declare(strict_types=1);

namespace Intervention\Image\Vips;

use FontLib\Exception\FontNotFoundException;
use FontLib\Font as FontReader;
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
     * {@inheritdoc}
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
                $color->red / 255,
                $color->green / 255,
                $color->blue / 255,
                $color->alpha / 255,
            ]
        );

        $angle = deg2rad($this->angle);
        $posy += $core->height * cos($angle);

        $angle = -1 * ($this->angle % 365);
        switch ($angle) {
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
                $core = $core->rotate($angle, [
                    'background' => [0],
                ]);
        }

        $align = is_null($this->align) ? 'left' : strtolower($this->align);
        $valign = is_null($this->valign) ? 'bottom' : strtolower($this->valign);

        $box = $this->getBoxSize();

        $posy -= $core->height;
        // correction on position depending on v/h alignment
        switch ($align.'-'.$valign) {

            case 'center-top':
                $posx -= round(($box[6] + $box[4]) / 2);
                $posy -= round(($box[7] + $box[5]) / 2);
                break;

            case 'right-top':
                $posx -= $box[4];
                $posy -= $box[5];
                break;

            case 'left-top':
                $posx -= $box[6];
                $posy -= $box[7];
                break;

            case 'center-center':
            case 'center-middle':
                $posx -= round(($box[0] + $box[4]) / 2);
                $posy -= round(($box[1] + $box[5]) / 2);
                break;

            case 'right-center':
            case 'right-middle':
                $posx -= round(($box[2] + $box[4]) / 2);
                $posy -= round(($box[3] + $box[5]) / 2);
                break;

            case 'left-center':
            case 'left-middle':
                $posx -= round(($box[0] + $box[6]) / 2);
                $posy -= round(($box[1] + $box[7]) / 2);
                break;

            case 'center-bottom':
                $posx -= round(($box[0] + $box[2]) / 2);
                $posy -= round(($box[1] + $box[3]) / 2);
                break;

            case 'right-bottom':
                $posx -= $box[2];
                $posy -= $box[3];
                break;

            case 'left-bottom':
                $posx -= $box[0];
                $posy -= $box[1];
                break;
        }

        /** @var ImageCore $imageCore */
        $imageCore = $image->getCore();

        if (! $imageCore->hasAlpha()) {
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
     * {@inheritdoc}
     * @throws Exception
     */
    public function getBoxSize()
    {
        $box = [];

        if ($this->text === '') {
            // no text -> no boxsize
            $box['width'] = 0;
            $box['height'] = 0;
        } else {
            $core = $this->createTextImage();
            $box = [
                $core->xoffset,
                $core->height,
                $core->width + $core->xoffset,
                $core->height,
                $core->width + $core->xoffset,
                0,
                $core->xoffset,
                0,
            ];

            if ($this->angle !== 0) {
                $angle = deg2rad(360 - $this->angle);

                for ($i = 0; $i < 4; $i++) {
                    $x = $box[$i * 2];
                    $y = $box[$i * 2 + 1];
                    $box[$i * 2] = cos($angle) * $x - sin($angle) * $y;
                    $box[$i * 2 + 1] = sin($angle) * $x + cos($angle) * $y;
                }
            }

            $box['width'] = (int) abs($box[4] - $box[0]);
            $box['height'] = (int) abs($box[5] - $box[1]);
        }

        return $box;
    }

    /**
     * @return ImageCore
     * @throws Exception|FontNotFoundException
     */
    private function createTextImage()
    {
        if ($this->hasApplicableFontFile()) {
            $font = FontReader::load($this->file);
        } else {
            throw new RuntimeException(
                'Font file must be provided to apply text to image.'
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
                'font' => $font->getFontFullName().' '.$this->size,
                'fontfile' => $this->file,
                'align' => $align_to_vips[$this->align ?: 'left'],
            ]
        );

        return $core;
    }
}
