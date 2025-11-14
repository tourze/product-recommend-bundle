<?php

declare(strict_types=1);

namespace ProductRecommendBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\DBAL\Types\Type;
use ProductRecommendBundle\DbalType\SnowflakeBigIntType;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\DoctrineIndexedBundle\DoctrineIndexedBundle;
use Tourze\EasyAdminMenuBundle\EasyAdminMenuBundle;
use Tourze\ProductCoreBundle\ProductCoreBundle;

class ProductRecommendBundle extends Bundle implements BundleDependencyInterface
{
    public function boot(): void
    {
        parent::boot();

        // 在测试环境中注册 Snowflake BigInt 类型
        // 使用 isset() 和反射来安全检查容器是否已初始化
        if (isset($this->container)) {
            try {
                $environment = $this->container->getParameter('kernel.environment');
                if ('test' === $environment) {
                    $this->registerTestDbalTypes();
                }
            } catch (\Exception) {
                // 容器未完全初始化或参数不存在，跳过DBAL类型注册
                // 这是正常情况，在单独调用boot()方法的测试中会发生
            }
        }
    }

    private function registerTestDbalTypes(): void
    {
        // 使用 Doctrine DBAL Type 系统覆盖默认的 BigInt 类型
        // 这会解决 Snowflake ID 在测试环境中返回整数导致 BigIntType 断言失败的问题
        if (!class_exists(Type::class)) {
            return;
        }

        $typeName = 'bigint';
        $typeClass = SnowflakeBigIntType::class;

        // 检查类型是否已注册
        if (!Type::hasType($typeName)) {
            Type::addType($typeName, $typeClass);
        } else {
            // 覆盖已存在的类型
            Type::overrideType($typeName, $typeClass);
        }
    }

    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
            DoctrineIndexedBundle::class => ['all' => true],
            ProductCoreBundle::class => ['all' => true],
            EasyAdminMenuBundle::class => ['all' => true],
        ];
    }
}
