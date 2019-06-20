<?php
namespace Tinyga\ImageOptimizerTests\Image;

use PHPUnit\Framework\TestCase;
use Tinyga\ImageOptimizer\Image\ImageParameters;

class ImageParametersTest extends TestCase
{
    function testImageParameters()
    {
        $params = new ImageParameters('image/jpeg', 12345, 640, 480);
        $this->assertSame('image/jpeg', $params->getMimeType());
        $this->assertSame(12345, $params->getFileSize());
        $this->assertSame(640, $params->getWidth());
        $this->assertSame(480, $params->getHeight());
        $this->assertSame([640, 480], $params->getResolution());
    }
}