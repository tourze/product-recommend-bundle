<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\ProductRecommendBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(ProductRecommendBundle::class)]
#[RunTestsInSeparateProcesses]
final class ProductRecommendBundleTest extends AbstractBundleTestCase
{
}
