<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Tests\DbalType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ProductRecommendBundle\DbalType\SnowflakeBigIntType;

/**
 * 验证 SnowflakeBigIntType 是否正确工作
 *
 * @internal
 */
#[CoversClass(SnowflakeBigIntType::class)]
final class SnowflakeBigIntTypeTest extends TestCase
{
    #[Test]
    public function testTypeNameIsCorrect(): void
    {
        $type = new SnowflakeBigIntType();
        $this->assertEquals('snowflake_bigint', $type->getName());
    }

    #[Test]
    public function testConvertToPHPValueNullHandling(): void
    {
        // Arrange
        $type = new SnowflakeBigIntType();
        $platform = $this->createMock(AbstractPlatform::class);

        // Act
        $result = $type->convertToPHPValue(null, $platform);

        // Assert
        $this->assertNull($result);
    }

    #[Test]
    public function testConvertToPHPValueConvertsIntegerToString(): void
    {
        // Arrange
        $type = new SnowflakeBigIntType();
        $platform = $this->createMock(AbstractPlatform::class);

        // Act
        $result = $type->convertToPHPValue(123456789, $platform);

        // Assert
        $this->assertIsString($result);
        $this->assertEquals('123456789', $result);
    }

    #[Test]
    public function testConvertToPHPValueKeepsStringAsString(): void
    {
        // Arrange
        $type = new SnowflakeBigIntType();
        $platform = $this->createMock(AbstractPlatform::class);

        // Act
        $result = $type->convertToPHPValue('987654321', $platform);

        // Assert
        $this->assertIsString($result);
        $this->assertEquals('987654321', $result);
    }

    #[Test]
    public function testConvertToPHPValueHandlesLargeSnowflakeIds(): void
    {
        // Arrange
        $type = new SnowflakeBigIntType();
        $platform = $this->createMock(AbstractPlatform::class);
        $largeSnowflakeId = 1234567890123456789; // 典型的Snowflake ID大小

        // Act
        $result = $type->convertToPHPValue($largeSnowflakeId, $platform);

        // Assert
        $this->assertIsString($result);
        $this->assertEquals('1234567890123456789', $result);
    }

    #[Test]
    public function testConvertToPHPValueHandlesNumericStrings(): void
    {
        // Arrange
        $type = new SnowflakeBigIntType();
        $platform = $this->createMock(AbstractPlatform::class);

        // Act
        $result = $type->convertToPHPValue('123.45', $platform);

        // Assert
        $this->assertIsString($result);
        $this->assertEquals('123.45', $result);
    }

    #[Test]
    public function testConvertToPHPValueConvertsOtherTypesToString(): void
    {
        // Arrange
        $type = new SnowflakeBigIntType();
        $platform = $this->createMock(AbstractPlatform::class);

        // Act & Assert - 测试布尔值
        $result = $type->convertToPHPValue(true, $platform);
        $this->assertIsString($result);
        $this->assertEquals('1', $result);

        // Act & Assert - 测试浮点数
        $result = $type->convertToPHPValue(42.5, $platform);
        $this->assertIsString($result);
        $this->assertEquals('42.5', $result);
    }
}
