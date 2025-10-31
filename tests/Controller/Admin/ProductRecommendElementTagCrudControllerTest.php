<?php

namespace ProductRecommendBundle\Tests\Controller\Admin;

use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\Controller\Admin\ProductRecommendElementTagCrudController;
use ProductRecommendBundle\Entity\RecommendElementTag;
use ProductRecommendBundle\Repository\RecommendElementTagRepository;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * @internal
 */
#[CoversClass(ProductRecommendElementTagCrudController::class)]
#[RunTestsInSeparateProcesses]
final class ProductRecommendElementTagCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    /** @phpstan-ignore-next-line missingType.generics */
    protected function getControllerService(): AbstractCrudController
    {
        return self::getService(ProductRecommendElementTagCrudController::class);
    }

    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '标签名' => ['标签名'];
        yield '有效' => ['有效'];
        yield '关联元素' => ['关联元素'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideNewPageFields(): iterable
    {
        yield '标签名' => ['title'];
        yield '有效' => ['valid'];
    }

    /**
     * @return iterable<string, array{string}>
     */
    public static function provideEditPageFields(): iterable
    {
        yield '标签名' => ['title'];
        yield '有效' => ['valid'];
    }

    public function testGetEntityFqcn(): void
    {
        $client = self::createClientWithDatabase();
        $admin = $this->createAdminUser('admin@test.com', 'password123');
        $this->loginAsAdmin($client, 'admin@test.com', 'password123');

        $client->catchExceptions(true);
        $client->request('GET', '/admin/product-recommend/element-tag');

        $this->assertSame(
            RecommendElementTag::class,
            ProductRecommendElementTagCrudController::getEntityFqcn()
        );
    }

    public function testEntityPersistence(): void
    {
        $client = self::createClientWithDatabase();
        $admin = $this->createAdminUser('admin@test.com', 'password123');
        $this->loginAsAdmin($client, 'admin@test.com', 'password123');

        $client->catchExceptions(true);
        $client->request('GET', '/admin/product-recommend/element-tag');

        $tag = new RecommendElementTag();
        $tag->setTitle('Test Tag');
        $tag->setValid(true);

        $entityManager = self::getService(ManagerRegistry::class)->getManager();
        $entityManager->persist($tag);
        $entityManager->flush();

        $this->assertNotNull($tag->getId());

        $repository = self::getService(RecommendElementTagRepository::class);
        $foundTag = $repository->find($tag->getId());
        $this->assertNotNull($foundTag);
        $this->assertSame('Test Tag', $foundTag->getTitle());
        $this->assertTrue($foundTag->isValid());
    }

    public function testUnauthorizedAccess(): void
    {
        $client = self::createClientWithDatabase();
        $client->catchExceptions(true);

        $client->request('GET', '/admin/product-recommend/element-tag');
        $this->assertTrue(
            $client->getResponse()->isRedirect() || 403 === $client->getResponse()->getStatusCode()
        );
    }
}
