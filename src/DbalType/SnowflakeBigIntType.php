<?php

declare(strict_types=1);

namespace ProductRecommendBundle\DbalType;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\BigIntType;

/**
 * 产品推荐Bundle专用的BigInt类型.
 *
 * 解决Snowflake ID在测试环境中返回整数而非字符串的问题
 * Doctrine DBAL期望bigint类型的值以字符串形式返回，以避免PHP整数溢出
 */
class SnowflakeBigIntType extends BigIntType
{
    /**
     * 将数据库值转换为PHP值.
     *
     * 确保Snowflake ID值总是以字符串形式返回，避免Doctrine的BigIntType断言失败
     *
     * @param mixed $value
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?string
    {
        if (null === $value) {
            return null;
        }

        // 将整数转换为字符串，确保与Doctrine BigIntType期望一致
        if (is_int($value)) {
            return (string) $value;
        }

        if (is_string($value)) {
            return $value;
        }

        // 处理其他数值类型
        if (is_numeric($value)) {
            return (string) $value;
        }

        // 对于其他类型，转换为字符串
        return (string) $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return 'snowflake_bigint';
    }
}
