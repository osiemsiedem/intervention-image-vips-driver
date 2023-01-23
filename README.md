# VIPS driver for Intervention Image

[![Latest Stable Version](https://poser.pugx.org/osiemsiedem/intervention-image-vips-driver/v/stable)](https://packagist.org/packages/osiemsiedem/intervention-image-vips-driver) [![License](https://poser.pugx.org/osiemsiedem/intervention-image-vips-driver/license)](https://packagist.org/packages/osiemsiedem/intervention-image-vips-driver)

This is a work in progress.

## Requirements

- PHP >= 7.2
- ext-vips >= 0.1.2 (or ext-ffi)

## Installation

```
composer require osiemsiedem/intervention-image-vips-driver
```

## Configuration

Set `vips` as driver name:

```php
use Intervention\Image\ImageManager;

$manager = new ImageManager(['driver' => 'vips']);
```

## To-Do

### Commands

- [x] BackupCommand
- [x] BlurCommand
- [x] BrightnessCommand
- [x] CropCommand
- [x] DestroyCommand
- [x] FitCommand
- [x] FlipCommand
- [x] GammaCommand
- [x] GetSizeCommand
- [x] GreyscaleCommand
- [x] HeightenCommand
- [x] InsertCommand
- [x] InvertCommand
- [x] OpacityCommand
- [x] PickColorCommand
- [x] PixelCommand
- [x] ResetCommand
- [x] ResizeCommand
- [x] ResizeCanvasCommand
- [x] RotateCommand
- [x] SharpenCommand
- [x] WidenCommand
- [ ] ColorizeCommand
- [ ] ContrastCommand
- [ ] FillCommand
- [ ] InterlaceCommand
- [ ] LimitColorsCommand
- [ ] MaskCommand
- [ ] PixelateCommand
- [ ] TrimCommand

### Shapes

- [ ] CircleShape
- [ ] EllipseShape
- [ ] LineShape
- [ ] PolygonShape
- [ ] RectangleShape

## Credits

- [Marek Szymczuk](https://github.com/bonzai)
- [All Contributors](../../contributors)

## License

Please see the [LICENSE.md](LICENSE.md) file.
