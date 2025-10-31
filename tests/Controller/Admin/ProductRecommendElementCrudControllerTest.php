<?php

namespace ProductRecommendBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\Controller\Admin\ProductRecommendElementCrudController;
use ProductRecommendBundle\Entity\RecommendElement;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * @internal
 */
#[CoversClass(ProductRecommendElementCrudController::class)]
#[RunTestsInSeparateProcesses]
final class ProductRecommendElementCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    public function testGetEntityFqcn(): void
    {
        $client = self::createClientWithDatabase();
        $admin = $this->createAdminUser('admin@test.com', 'password123');
        $this->loginAsAdmin($client, 'admin@test.com', 'password123');

        // 测试实际的页面访问
        $client->request('GET', '/admin');
        // 设置静态客户端以支持响应断言
        self::getClient($client);
        $this->assertResponseIsSuccessful();

        // 通过反射验证静态方法
        $reflection = new \ReflectionClass(ProductRecommendElementCrudController::class);
        $method = $reflection->getMethod('getEntityFqcn');
        $result = $method->invoke(null);

        $this->assertSame(
            RecommendElement::class,
            $result
        );
    }

    public function testEntityPersistence(): void
    {
        $client = self::createClientWithDatabase();
        $admin = $this->createAdminUser('admin@test.com', 'password123');
        $this->loginAsAdmin($client, 'admin@test.com', 'password123');

        // 测试管理后台主页可以访问
        $client->request('GET', '/admin');
        // 设置静态客户端以支持响应断言
        self::getClient($client);
        $this->assertResponseIsSuccessful();

        // 验证实体类可以通过反射创建
        $reflection = new \ReflectionClass(RecommendElement::class);
        $element = $reflection->newInstance();
        $this->assertInstanceOf(RecommendElement::class, $element);

        // 验证实体方法可以正常调用
        $element->setSpuId('123');
        $element->setValid(true);
        $element->setTextReason('Test reason');
        $this->assertEquals('123', $element->getSpuId());
        $this->assertTrue($element->isValid());
        $this->assertEquals('Test reason', $element->getTextReason());
    }

    /**
     * @phpstan-ignore-next-line missingType.generics
     */
    protected function getControllerService(): AbstractCrudController
    {
        $controller = self::getService(ProductRecommendElementCrudController::class);
        $this->assertInstanceOf(AbstractCrudController::class, $controller);

        return $controller;
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '推荐位' => ['推荐位'];
        yield '商品ID' => ['商品ID'];
        yield '图片' => ['图片'];
        yield '推荐理由' => ['推荐理由'];
        yield '目标分组' => ['目标分组'];
        yield '分数' => ['分数'];
        yield '排序' => ['排序'];
        yield '有效' => ['有效'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield 'block' => ['block'];
        yield 'spuId' => ['spuId'];
        yield 'thumb' => ['thumb'];
        yield 'textReason' => ['textReason'];
        yield 'targetGroup' => ['targetGroup'];
        yield 'score' => ['score'];
        yield 'sortNumber' => ['sortNumber'];
        yield 'valid' => ['valid'];
        yield 'recommendElementTags' => ['recommendElementTags'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield 'block' => ['block'];
        yield 'spuId' => ['spuId'];
        yield 'thumb' => ['thumb'];
        yield 'textReason' => ['textReason'];
        yield 'targetGroup' => ['targetGroup'];
        yield 'score' => ['score'];
        yield 'sortNumber' => ['sortNumber'];
        yield 'valid' => ['valid'];
        yield 'recommendElementTags' => ['recommendElementTags'];
    }

    public function testUnauthorizedAccess(): void
    {
        $client = self::createClientWithDatabase();

        $client->catchExceptions(true);
        $client->request('GET', '/admin/product-recommend/element');
        // 设置静态客户端以支持响应断言
        self::getClient($client);
        $this->assertResponseRedirects('/login');

        $client->request('GET', '/admin/product-recommend/element/new');
        // 设置静态客户端以支持响应断言
        self::getClient($client);
        $this->assertResponseRedirects('/login');
    }
}
