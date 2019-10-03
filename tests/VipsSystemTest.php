<?php

namespace Intervention\Image\Vips\Tests;

use Intervention\Image\Image as InterventionImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Size;
use Jcupitt\Vips\Image as VipsImage;
use PHPUnit\Framework\TestCase;

class VipsSystemTest extends TestCase
{

    /**
     * @var ImageManager
     */
    private $manager;

    protected function setUp()
    {
        parent::setUp();

        $this->manager = new ImageManager([
            'driver' => 'vips',
        ]);
    }

    public function testMakeFromPath(): void
    {
        $img = $this->manager->make('tests/images/circle.png');
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
        $this->assertEquals('image/png', $img->mime);
        $this->assertEquals('tests/images', $img->dirname);
        $this->assertEquals('circle.png', $img->basename);
        $this->assertEquals('png', $img->extension);
        $this->assertEquals('circle', $img->filename);
        $this->assertEquals('image/png', $img->mime);
    }

    /**
     * @expectedException \Intervention\Image\Exception\NotReadableException
     */
    public function testMakeFromNotExisting(): void
    {
        $this->manager->make('tests/images/not_existing.png');
    }

    public function testMakeFromString(): void
    {
        $str = file_get_contents('tests/images/circle.png');
        $img = $this->manager->make($str);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
        $this->assertEquals('image/png', $img->mime);
    }

    public function testMakeFromDataUrl(): void
    {
        $img = $this->manager
            ->make('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3cRvs4UAAAAAElFTkSuQmCC');
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
    }

    public function testMakeFromBase64(): void
    {
        $img = $this->manager
            ->make('iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3cRvs4UAAAAAElFTkSuQmCC');
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
    }

    public function testMakeFromBase64WithNewlines(): void
    {
        $data = 'iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+' . "\n" .
                '9AAAAGElEQVQYlWM8c+bMfwYiABMxikYVUk8hAHWzA3' . "\n" .
                'cRvs4UAAAAAElFTkSuQmCC';
        $img  = $this->manager->make($data);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(10, $img->getWidth());
        $this->assertEquals(10, $img->getHeight());
    }

    public function testCanvas(): void
    {
        $img = $this->manager->canvas(30, 20);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(30, $img->getWidth());
        $this->assertEquals(20, $img->getHeight());
        $this->assertTransparentPosition($img, 0, 0);
    }

    public function testCanvasWithSolidBackground(): void
    {
        $img = $this->manager->canvas(30, 20, 'b53717');
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(30, $img->getWidth());
        $this->assertEquals(20, $img->getHeight());
        $this->assertEquals('#b53717', $img->pickColor(15, 15, 'hex'));
    }

    public function testGetSize(): void
    {
        $img  = $this->manager->make('tests/images/tile.png');
        $size = $img->getSize();
        $this->assertInstanceOf(Size::class, $size);
        $this->assertIsInt($size->width);
        $this->assertIsInt($size->height);
        $this->assertEquals(16, $size->width);
        $this->assertEquals(16, $size->height);
    }

    public function testResizeImage(): void
    {
        $img = $this->manager->make('tests/images/circle.png');
        $img->resize(120, 150);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(120, $img->getWidth());
        $this->assertEquals(150, $img->getHeight());
        $this->assertTransparentPosition($img, 0, 0);
    }

    public function testResizeImageOnlyWidth(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->resize(120, null);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(120, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertTransparentPosition($img, 0, 15);
    }

    public function testResizeImageOnlyHeight(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->resize(null, 150);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(150, $img->getHeight());
        $this->assertTransparentPosition($img, 15, 0);
    }

    public function testResizeImageAutoHeight(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->resize(50, null, static function ($constraint) {
            $constraint->aspectRatio();
        });
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
        $this->assertTransparentPosition($img, 30, 0);
    }

    public function testResizeImageAutoWidth(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->resize(null, 50, static function ($constraint) {
            $constraint->aspectRatio();
        });
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(50, $img->getHeight());
        $this->assertTransparentPosition($img, 30, 0);
    }

    public function testResizeDominantWidth(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->resize(100, 120, static function ($constraint) {
            $constraint->aspectRatio();
        });
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(100, $img->getWidth());
        $this->assertEquals(100, $img->getHeight());
        $this->assertTransparentPosition($img, 60, 0);
    }

    public function testResizeImagePreserveSimpleUpsizing(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->resize(100, 100, static function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertTransparentPosition($img, 15, 0);
    }

    public function testWidenImage(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->widen(100);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(100, $img->getWidth());
        $this->assertEquals(100, $img->getHeight());
        $this->assertTransparentPosition($img, 60, 0);
    }

    public function testWidenImageWithConstraint(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->widen(100, static function ($constraint) {
            $constraint->upsize();
        });
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertTransparentPosition($img, 8, 0);
    }

    public function testHeightenImage(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->heighten(100);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(100, $img->getWidth());
        $this->assertEquals(100, $img->getHeight());
        $this->assertTransparentPosition($img, 60, 0);
    }

    public function testHeightenImageWithConstraint(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->heighten(100, static function ($constraint) {
            $constraint->upsize();
        });
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertInstanceOf(VipsImage::class, $img->getCore());
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertTransparentPosition($img, 8, 0);
    }

    public function testCropImage(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->crop(6, 6); // should be centered without pos.
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(6, $img->getWidth());
        $this->assertEquals(6, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 2);
        $this->assertColorAtPosition('#445160', $img, 3, 3);
        $this->assertTransparentPosition($img, 0, 3);
        $this->assertTransparentPosition($img, 3, 2);
    }

    public function testCropImageWithPosition(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->crop(4, 4, 7, 7); // should be centered without pos.
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(4, $img->getWidth());
        $this->assertEquals(4, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 0);
        $this->assertColorAtPosition('#445160', $img, 1, 1);
        $this->assertTransparentPosition($img, 0, 1);
        $this->assertTransparentPosition($img, 1, 0);
    }

    public function testFitImageSquare(): void
    {
        $this->markTestIncomplete('PNG resizing produces wrong colors due to alpha blending into the pixels');

        $img = $this->manager->make('tests/images/tile.png');
        $img->fit(6);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(6, $img->getWidth());
        $this->assertEquals(6, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 2);
        $this->assertColorAtPosition('#445060', $img, 3, 3);
        $this->assertTransparentPosition($img, 0, 3);
        $this->assertTransparentPosition($img, 3, 2);
    }

    public function testFitImageRectangle(): void
    {
        $this->markTestIncomplete('PNG resizing produces wrong colors due to alpha blending into the pixels');

        $img = $this->manager->make('tests/images/tile.png');
        $img->fit(12, 6);

        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(12, $img->getWidth());
        $this->assertEquals(6, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 2);
        $this->assertColorAtPosition('#445160', $img, 6, 3);
        $this->assertTransparentPosition($img, 0, 3);
        $this->assertTransparentPosition($img, 6, 2);
    }

    public function testFitImageWithConstraintUpsize(): void
    {
        $this->markTestIncomplete('PNG resizing produces wrong colors due to alpha blending into the pixels');

        $img = $this->manager->make('tests/images/trim.png');
        $img->fit(300, 150, static function ($constraint) {
            $constraint->upsize();
        });
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(50, $img->getWidth());
        $this->assertEquals(25, $img->getHeight());
        $this->assertColorAtPosition('#00aef0', $img, 0, 0);
        $this->assertColorAtPosition('#afa94c', $img, 17, 0);
        $this->assertColorAtPosition('#ffa601', $img, 24, 0);
    }

    public function testFlipImageHorizontal(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->flip('h');
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 8, 7);
        $this->assertColorAtPosition('#445160', $img, 0, 8);
        $this->assertTransparentPosition($img, 0, 7);
        $this->assertTransparentPosition($img, 8, 8);
    }

    public function testFlipImageVertical(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->flip('v');
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 7);
        $this->assertTransparentPosition($img, 0, 7);
        $this->assertTransparentPosition($img, 8, 8);
    }

    public function testRotateImage(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->rotate(90);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 7);
        $this->assertTransparentPosition($img, 0, 7);
        $this->assertTransparentPosition($img, 8, 8);
    }

    public function testInsertImage(): void
    {
        $watermark = $this->manager->canvas(16, 16, '#0000ff'); // create watermark
        // top-left anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top-left', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(16, 16, 'hex'));
        // top-left anchor coordinates
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top-left', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(9, 9, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(10, 10, 'hex'));
        // top anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(0, 0, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(23, 15, 'hex'));
        // top anchor coordinates
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(18, 10, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(31, 26, 'hex'));
        // top-right anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top-right', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(15, 0, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(31, 0, 'hex'));
        // top-right anchor coordinates
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'top-right', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(6, 9, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(21, 25, 'hex'));
        // left anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'left', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(15, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(0, 7, 'hex'));
        // left anchor coordinates
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'left', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(8, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(10, 7, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(25, 23, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(25, 8, 'hex'));
        // right anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'right', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(31, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(15, 15, 'hex'));
        // right anchor coordinates
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'right', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(5, 8, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(22, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(21, 7, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(6, 8, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(21, 23, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(6, 23, 'hex'));
        // bottom-left anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom-left', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(15, 31, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(0, 15, 'hex'));
        // bottom-left anchor coordinates
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom-left', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(10, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(9, 20, 'hex'));
        // bottom anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(8, 16, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(8, 15, 'hex'));
        // bottom anchor coordinates
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#ff0000', $img->pickColor(5, 8, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(23, 22, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(24, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(7, 6, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(8, 6, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(23, 21, 'hex'));
        $this->assertEquals('#0000ff', $img->pickColor(23, 6, 'hex'));
        // bottom-right anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom-right', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(16, 16, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(15, 16, 'hex'));
        // bottom-right anchor coordinates
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'bottom-right', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(21, 21, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(22, 22, 'hex'));
        // center anchor
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'center', 0, 0);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(23, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(8, 7, 'hex'));
        // center anchor coordinates / coordinates will be ignored for center
        $img = $this->manager->canvas(32, 32, '#ff0000'); // create canvas
        $img->insert($watermark, 'center', 10, 10);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals($img->getWidth(), 32);
        $this->assertEquals($img->getHeight(), 32);
        $this->assertEquals('#0000ff', $img->pickColor(23, 23, 'hex'));
        $this->assertEquals('#ff0000', $img->pickColor(8, 7, 'hex'));
    }

    public function testInsertWithAlphaChannel(): void
    {
        $img = $this->manager->canvas(50, 50, 'ff0000');
        $img->insert('tests/images/circle.png');
        $this->assertColorAtPosition('#ff0000', $img, 0, 0);
        $this->assertColorAtPosition('#320000', $img, 30, 30);
    }

    public function testInsertAfterResize(): void
    {
        $img = $this->manager->make('tests/images/trim.png');
        $img->resize(16, 16)->insert('tests/images/tile.png');
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#00aef0', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#ffa601', $img, 8, 7);
    }

    public function testInsertBinary(): void
    {
        $data = file_get_contents('tests/images/tile.png');
        $img  = $this->manager->make('tests/images/trim.png');
        $img->insert($data);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#00aef0', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#00aef0', $img, 8, 7);
        $this->assertColorAtPosition('#ffa601', $img, 24, 24);
    }

    public function testInsertInterventionImage(): void
    {
        $obj = $this->manager->make('tests/images/tile.png');
        $img = $this->manager->make('tests/images/trim.png');
        $img->insert($obj);
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertColorAtPosition('#b4e000', $img, 0, 7);
        $this->assertColorAtPosition('#00aef0', $img, 0, 8);
        $this->assertColorAtPosition('#445160', $img, 8, 8);
        $this->assertColorAtPosition('#00aef0', $img, 8, 7);
        $this->assertColorAtPosition('#ffa601', $img, 24, 24);
    }

    public function testOpacity(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->opacity(50);
        $checkColor = $img->pickColor(7, 7, 'array');
        $this->assertEquals($checkColor[0], 180);
        $this->assertEquals($checkColor[1], 224);
        $this->assertEquals($checkColor[2], 0);
        $this->assertEquals($checkColor[3], 0.5);
        $checkColor = $img->pickColor(8, 8, 'array');
        $this->assertEquals($checkColor[0], 68);
        $this->assertEquals($checkColor[1], 81);
        $this->assertEquals($checkColor[2], 96);
        $this->assertEquals($checkColor[3], 0.5);
        $this->assertTransparentPosition($img, 0, 11);
    }

    public function testGreyscaleImage(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->greyscale();
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertTransparentPosition($img, 8, 0);
        $this->assertColorAtPosition('#cdcdcd', $img, 0, 0);
    }

    public function testInvertImage(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->invert();
        $this->assertInstanceOf(InterventionImage::class, $img);
        $this->assertTransparentPosition($img, 8, 0);
        $this->assertColorAtPosition('#4b1fff', $img, 0, 0);
    }

    public function testBlurImage(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->blur(1);
        $this->assertInstanceOf(InterventionImage::class, $img);
    }

    public function testPixelImage(): void
    {
        $img    = $this->manager->make('tests/images/tile.png');
        $coords = [[5, 5], [12, 12]];
        $img    = $img->pixel('fdf5e4', $coords[0][0], $coords[0][1]);
        $img    = $img->pixel([255, 255, 255], $coords[1][0], $coords[1][1]);
        $this->assertEquals('#fdf5e4', $img->pickColor($coords[0][0], $coords[0][1], 'hex'));
        $this->assertEquals('#ffffff', $img->pickColor($coords[1][0], $coords[1][1], 'hex'));
    }

    public function testResetImage(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->backup();
        $img->resize(30, 20);
        $img->reset();
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
    }

    public function testResetEmptyImage(): void
    {
        $img = $this->manager->canvas(16, 16, '#0000ff');
        $img->backup();
        $img->resize(30, 20);
        $img->fill('#ff0000');
        $img->reset();
        $this->assertIsInt($img->getWidth());
        $this->assertIsInt($img->getHeight());
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        $this->assertColorAtPosition('#0000ff', $img, 0, 0);
    }

    public function testResetKeepTransparency(): void
    {
        $img = $this->manager->make('tests/images/circle.png');
        $img->backup();
        $img->reset();
        $this->assertTransparentPosition($img, 0, 0);
    }

    public function testResetToNamed(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $img->backup('original');
        $img->resize(30, 20);
        $img->backup('30x20');
        // reset to original
        $img->reset('original');
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
        // reset to 30x20
        // $img->reset('30x20');
        // $this->assertEquals(30, $img->getWidth());
        // $this->assertEquals(20, $img->getHeight());
        // reset to original again
        $img->reset('original');
        $this->assertEquals(16, $img->getWidth());
        $this->assertEquals(16, $img->getHeight());
    }

    public function testPickColorFromTrueColor(): void
    {
        $img = $this->manager->make('tests/images/star.png');
        $c   = $img->pickColor(0, 0);
        $this->assertEquals(255, $c[0]);
        $this->assertEquals(255, $c[1]);
        $this->assertEquals(255, $c[2]);
        $this->assertEquals(0, $c[3]);
        $c = $img->pickColor(11, 11);
        $this->assertEquals(34, $c[0]);
        $this->assertEquals(0, $c[1]);
        $this->assertEquals(160, $c[2]);
        $this->assertEquals(0.47, $c[3]);
        $c = $img->pickColor(16, 16);
        $this->assertEquals(231, $c[0]);
        $this->assertEquals(0, $c[1]);
        $this->assertEquals(18, $c[2]);
        $this->assertEquals(1, $c[3]);
    }

    public function testPickColorFromIndexed(): void
    {
        $img = $this->manager->make('tests/images/tile.png');
        $c   = $img->pickColor(0, 0);
        $this->assertEquals(180, $c[0]);
        $this->assertEquals(224, $c[1]);
        $this->assertEquals(0, $c[2]);
        $this->assertEquals(1, $c[3]);
        $c = $img->pickColor(8, 8);
        $this->assertEquals(68, $c[0]);
        $this->assertEquals(81, $c[1]);
        $this->assertEquals(96, $c[2]);
        $this->assertEquals(1, $c[3]);
        $c = $img->pickColor(0, 15);
        $this->assertEquals(0, $c[0]);
        $this->assertEquals(0, $c[1]);
        $this->assertEquals(0, $c[2]);
        $this->assertEquals(0, $c[3]);
    }

    public function testGammaImage(): void
    {
        $this->markTestIncomplete('Pixel color values are slightly off');

        $img = $this->manager->make('tests/images/trim.png');
        $img->gamma(1.6);
        $this->assertColorAtPosition('#00c9f6', $img, 0, 0);
        $this->assertColorAtPosition('#ffc308', $img, 24, 24);
    }

    public function testBrightnessImage(): void
    {
        $img = $this->manager->make('tests/images/trim.png');
        $img->brightness(35);
        $this->assertColorAtPosition('#59ffff', $img, 0, 0);
        $this->assertColorAtPosition('#ffff5a', $img, 24, 24);
    }

    public function testEncodeDefault(): void
    {
        $img = $this->manager->make('tests/images/trim.png');
        $img->encode();
        $this->assertInstanceOf(InterventionImage::class, $this->manager->make($img->encoded));
    }

    public function testEncodeJpeg(): void
    {
        $img = $this->manager->make('tests/images/trim.png');
        $img->encode('jpg');
        $this->assertInstanceOf(InterventionImage::class, $this->manager->make($img->encoded));
    }

    public function testEncodeWebp(): void
    {
        $img  = $this->manager->make('tests/images/trim.png');
        $data = (string)$img->encode('webp');
        $this->assertEquals('image/webp; charset=binary', $this->getMime($data));
    }

    public function testEncodeDataUrl(): void
    {
        $img = $this->manager->make('tests/images/trim.png');
        $img->encode('data-url');
        $this->assertEquals('data:image/png;base64', substr($img->encoded, 0, 21));
    }

    public function testExifReadAll(): void
    {
        $img  = $this->manager->make('tests/images/exif.jpg');
        $data = $img->exif();
        $this->assertInternalType('array', $data);
        $this->assertEquals(19, count($data));
    }

    public function testExifReadKey(): void
    {
        $img  = $this->manager->make('tests/images/exif.jpg');
        $data = $img->exif('Artist');
        $this->assertInternalType('string', $data);
        $this->assertEquals('Oliver Vogel', $data);
    }

    public function testExifReadNotExistingKey(): void
    {
        $img  = $this->manager->make('tests/images/exif.jpg');
        $data = $img->exif('xxx');
        $this->assertEquals(null, $data);
    }

    public function testSaveImage(): void
    {
        $save_as = 'tests/tmp/foo.jpg';
        $img     = $this->manager->make('tests/images/trim.png');
        $img->save($save_as, 80);
        $this->assertFileExists($save_as);
        $this->assertEquals($img->dirname, 'tests/tmp');
        $this->assertEquals($img->basename, 'foo.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'foo');
        $this->assertEquals($img->mime, 'image/jpeg');
        @unlink($save_as);
        $save_as = 'tests/tmp/foo.png';
        $img     = $this->manager->make('tests/images/trim.png');
        $img->save($save_as);
        $this->assertEquals($img->dirname, 'tests/tmp');
        $this->assertEquals($img->basename, 'foo.png');
        $this->assertEquals($img->extension, 'png');
        $this->assertEquals($img->filename, 'foo');
        $this->assertEquals($img->mime, 'image/png');
        $this->assertFileExists($save_as);
        @unlink($save_as);
        $save_as = 'tests/tmp/foo.jpg';
        $img     = $this->manager->make('tests/images/trim.png');
        $img->save($save_as, 0);
        $this->assertEquals($img->dirname, 'tests/tmp');
        $this->assertEquals($img->basename, 'foo.jpg');
        $this->assertEquals($img->extension, 'jpg');
        $this->assertEquals($img->filename, 'foo');
        $this->assertEquals($img->mime, 'image/jpeg');
        $this->assertFileExists($save_as);
        @unlink($save_as);
    }

    public function testSaveImageWithoutParameter(): void
    {
        $path = 'tests/tmp/bar.png';
        // create temp. test image (red)
        $img = $this->manager->canvas(16, 16, '#ff0000');
        $img->save($path);
        $img->destroy();
        // open test image again
        $img = $this->manager->make($path);
        $this->assertColorAtPosition('#ff0000', $img, 0, 0);
        // fill with green and save wthout paramater
        //$img->fill('#00ff00');
        //$img->save();
        //$img->destroy();
        //// re-open test image (should be green)
        //$img = $this->manager->make($path);
        //$this->assertColorAtPosition('#00ff00', $img, 0, 0);
        //$img->destroy();
        @unlink($path);
    }

    public function testStringConversion(): void
    {
        $img   = $this->manager->make('tests/images/trim.png');
        $value = (string)$img;
        $this->assertIsString($value);
    }

    private function assertColorAtPosition($color, $img, $x, $y): void
    {
        $pick = $img->pickColor($x, $y, 'hex');
        $this->assertEquals($color, $pick);
        $this->assertInstanceOf(InterventionImage::class, $img);
    }

    private function assertTransparentPosition($img, $x, $y, $transparent = 0): void
    {
        // background should be transparent
        $color = $img->pickColor($x, $y, 'array');
        $this->assertEquals($transparent, $color[3]); // alpha channel
    }

    private function getMime($data)
    {
        $finfo = new \finfo(FILEINFO_MIME);

        return $finfo->buffer($data);
    }
}
