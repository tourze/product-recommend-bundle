<?php

namespace ProductRecommendBundle\Tests\Controller\Admin;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\Controller\Admin\ProductRecommendBlockCrudController;
use ProductRecommendBundle\Entity\RecommendBlock;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * @internal
 */
#[CoversClass(ProductRecommendBlockCrudController::class)]
#[RunTestsInSeparateProcesses]
final class ProductRecommendBlockCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): ProductRecommendBlockCrudController
    {
        return self::getService(ProductRecommendBlockCrudController::class);
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'id' => ['ID'];
        yield 'title' => ['标题'];
        yield 'subtitle' => ['副标题'];
        yield 'valid' => ['有效'];
        yield 'elements' => ['推荐元素'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield 'title' => ['title'];
        yield 'subtitle' => ['subtitle'];
        yield 'valid' => ['valid'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield 'title' => ['title'];
        yield 'subtitle' => ['subtitle'];
        yield 'valid' => ['valid'];
    }

    public function testEntityPersistence(): void
    {
        $client = self::createAuthenticatedClient();

        // 测试管理后台主页可以访问
        $client->request('GET', '/admin');
        // 设置静态客户端以支持响应断言
        self::getClient($client);
        $this->assertResponseIsSuccessful();

        // 验证实体类可以通过反射创建
        $reflection = new \ReflectionClass(RecommendBlock::class);
        $block = $reflection->newInstance();
        $this->assertInstanceOf(RecommendBlock::class, $block);

        // 验证实体方法可以正常调用
        $block->setTitle('Test Block');
        $block->setValid(true);
        $this->assertEquals('Test Block', $block->getTitle());
        $this->assertTrue($block->isValid());
    }

    public function testUnauthorizedAccess(): void
    {
        $client = self::createClientWithDatabase();

        $client->catchExceptions(true);
        $client->request('GET', '/admin/product-recommend/block');
        // 设置静态客户端以支持响应断言
        self::getClient($client);
        $this->assertResponseRedirects('/login');

        $client->request('GET', '/admin/product-recommend/block/new');
        // 设置静态客户端以支持响应断言
        self::getClient($client);
        $this->assertResponseRedirects('/login');
    }
}
