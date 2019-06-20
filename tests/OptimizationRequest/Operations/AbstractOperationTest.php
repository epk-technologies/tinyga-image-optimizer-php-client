<?php
namespace Tinyga\ImageOptimizerTests\Image;

use PHPUnit\Framework\TestCase;

abstract class AbstractOperationTest extends TestCase
{
    abstract function testConstructor();

    abstract function testSetupOperation();

    abstract function testInitFromString();

    abstract function testInitFromArray();
}