<?php
namespace Tinyga\ImageOptimizerTests\Image;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations\OperationFlip;

class OperationFlipTest extends AbstractOperationTest
{
    function testWrongAxis()
    {
        $this->expectException(\InvalidArgumentException::class);
        new OperationFlip('wrong axis');
    }

    function testSetupOperation()
    {
        $operation = new OperationFlip();
        $this->assertFalse($operation->isHorizontalFlip());
        $this->assertFalse($operation->isVerticalFlip());

        $operation->setAxis(OperationFlip::AXIS_X);
        $this->assertTrue($operation->isVerticalFlip());
        $this->assertFalse($operation->isHorizontalFlip());

        $operation->setAxis(OperationFlip::AXIS_Y);
        $this->assertFalse($operation->isVerticalFlip());
        $this->assertTrue($operation->isHorizontalFlip());
    }

    function testInitFromString()
    {
        $operation = new OperationFlip();
        $operation->initFromString(OperationFlip::AXIS_X);
        $this->assertTrue($operation->isVerticalFlip());

        $operation = new OperationFlip();
        $operation->initFromString(OperationFlip::AXIS_Y);
        $this->assertTrue($operation->isHorizontalFlip());
    }

    function testInitFromArray()
    {
        $operation = new OperationFlip();
        $operation->initFromArray(['axis' => OperationFlip::AXIS_X]);
        $this->assertTrue($operation->isVerticalFlip());

        $operation = new OperationFlip();
        $operation->initFromArray(['axis' => OperationFlip::AXIS_Y]);
        $this->assertTrue($operation->isHorizontalFlip());
    }

    function testConstructor()
    {
        $operation = new OperationFlip(OperationFlip::AXIS_X);
        $this->assertTrue($operation->isVerticalFlip());

        $operation = new OperationFlip(OperationFlip::AXIS_Y);
        $this->assertTrue($operation->isHorizontalFlip());
    }
}