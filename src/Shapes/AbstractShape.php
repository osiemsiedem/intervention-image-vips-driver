<?php

declare(strict_types=1);

namespace Intervention\Image\Vips\Shapes;

use Exception;
use Intervention\Image\AbstractShape as BaseAbstractShape;
use Intervention\Image\Image;
use Intervention\Image\Vips\Color;
use Jcupitt\Vips\BlendMode;

abstract class AbstractShape extends BaseAbstractShape
{
    protected function getSVGAttributes(): array
    {
        $attributes['fill'] = 'none';
        if ($this->background) {
            $attributes['fill'] = (new Color($this->background))->getRgba();
        }
        if ($this->border_width) {
            $attributes['stroke'] = (new Color($this->border_color))->getRgba();
            $attributes['stroke-width'] = $this->border_width;
        }

        return $attributes;
    }

    /**
     * @param Image  $image
     * @param string $shape
     * @param array  $attributes
     * @return bool
     */
    protected function applyToImageViaSVG(
        Image $image,
        $shape,
        array $attributes = []
    ): bool {
        try {
            /** @var \Jcupitt\Vips\Image $core */
            $core = $image->getCore();

            $xml_attributes = implode(
                ' ',
                array_map(
                    static function ($key, $value) {
                        return sprintf(
                            '%s="%s"',
                            $key,
                            htmlspecialchars((string) $value)
                        );
                    },
                    array_keys($attributes),
                    $attributes
                )
            );

            $svg = <<<EOL
<svg viewBox="0 0 {$image->width()} {$image->height()}" xmlns="http://www.w3.org/2000/svg">
    <{$shape} {$xml_attributes} />
</svg>
EOL;
            $svgImage = $image->getDriver()->init($svg)->getCore();

            $core = $core->composite([$svgImage], [BlendMode::OVER]);

            $image->setCore($core);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }
}
