<?php

namespace ProductRecommendBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use ProductRecommendBundle\DependencyInjection\ProductRecommendExtension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(ProductRecommendExtension::class)]
final class ProductRecommendExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
}
