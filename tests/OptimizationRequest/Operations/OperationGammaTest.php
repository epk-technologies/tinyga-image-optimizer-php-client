<?php
namespace Tinyga\ImageOptimizerTests\Image;

use Tinyga\ImageOptimizer\OptimizationRequest\Operations\OperationGamma;

class OperationGammaTest extends AbstractOperationTest
{
    function testWrongMinGamma()
    {
        $this->expectException(\InvalidArgumentException::class);
        new OperationGamma(OperationGamma::MIN_GAMMA - 0.1);
    }

    function testWrongMaxGamma()
    {
        $this->expectException(\InvalidArgumentException::class);
        new OperationGamma(OperationGamma::MAX_GAMMA + 0.1);
    }

    function testSetupOperation()
    {
        $operation = new OperationGamma();
        $this->assertSame(OperationGamma::DEFAULT_GAMMA, $operation->getGamma());
        $operation->setGamma(2.0);
        $this->assertSame(2.0, $operation->getGamma());

        $operation->setGamma(3);
        $this->assertSame(3.0, $operation->getGamma());
    }

    function testInitFromString()
    {
        $operation = new OperationGamma();
        $operation->initFromString('2.5');
        $this->assertSame(2.5, $operation->getGamma());
    }

    function testInitFromArray()
    {
        $operation = new OperationGamma();
        $operation->initFromArray(['gamma' => 2.5]);
        $this->assertSame(2.5, $operation->getGamma());
    }

    function testConstructor()
    {
        $operation = new OperationGamma(2.5);
        $this->assertSame(2.5, $operation->getGamma());
    }
}