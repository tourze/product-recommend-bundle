<?php

namespace ProductRecommendBundle\Tests\Controller\Admin;

use Doctrine\Persistence\ManagerRegistry;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\Controller\Admin\ProductRecommendRelatedCrudController;
use ProductRecommendBundle\Entity\RelatedRecommend;
use ProductRecommendBundle\Repository\RelatedRecommendRepository;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;

/**
 * @internal
 */
#[CoversClass(ProductRecommendRelatedCrudController::class)]
#[RunTestsInSeparateProcesses]
final class ProductRecommendRelatedCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    protected function getControllerService(): AbstractCrudController&ProductRecommendRelatedCrudController
    {
        return self::getService(ProductRecommendRelatedCrudController::class);
    }

    /** @return iterable<string, array{string}> */
    public static function provideIndexPageHeaders(): iterable
    {
        yield 'ID' => ['ID'];
        yield '访问SPUID' => ['访问SPUID'];
        yield '场景' => ['场景'];
        yield '推荐SPUID' => ['推荐SPUID'];
        yield '推荐原因' => ['推荐原因'];
        yield '目标分组' => ['目标分组'];
        yield '分数' => ['分数'];
        yield '次序值' => ['次序值'];
        yield '有效' => ['有效'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideNewPageFields(): iterable
    {
        yield '访问SPUID' => ['visitSpuId'];
        yield '场景' => ['scene'];
        yield '推荐SPUID' => ['recommendSpuId'];
        yield '推荐原因' => ['textReason'];
        yield '目标分组' => ['targetGroup'];
        yield '分数' => ['score'];
        yield '次序值' => ['sortNumber'];
        yield '有效' => ['valid'];
    }

    /** @return iterable<string, array{string}> */
    public static function provideEditPageFields(): iterable
    {
        yield '访问SPUID' => ['visitSpuId'];
        yield '场景' => ['scene'];
        yield '推荐SPUID' => ['recommendSpuId'];
        yield '推荐原因' => ['textReason'];
        yield '目标分组' => ['targetGroup'];
        yield '分数' => ['score'];
        yield '次序值' => ['sortNumber'];
        yield '有效' => ['valid'];
    }

    public function testEntityPersistence(): void
    {
        $client = self::createAuthenticatedClient();

        $client->catchExceptions(true);
        $client->request('GET', '/admin/product-recommend/related');

        $related = new RelatedRecommend();
        $related->setVisitSpuId('123');
        $related->setRecommendSpuId('456');
        $related->setScene('homepage');
        $related->setValid(true);
        $related->setTextReason('Test reason');
        $related->setTargetGroup('all');
        $related->setScore(9.0);

        $entityManager = self::getService(ManagerRegistry::class)->getManager();
        $entityManager->persist($related);
        $entityManager->flush();

        $this->assertNotNull($related->getId());

        $repository = self::getService(RelatedRecommendRepository::class);
        $foundRelated = $repository->find($related->getId());
        $this->assertNotNull($foundRelated);
        $this->assertSame('123', $foundRelated->getVisitSpuId());
        $this->assertSame('456', $foundRelated->getRecommendSpuId());
        $this->assertSame('homepage', $foundRelated->getScene());
        $this->assertTrue($foundRelated->isValid());
        $this->assertSame('Test reason', $foundRelated->getTextReason());
        $this->assertSame('all', $foundRelated->getTargetGroup());
        $this->assertSame(9.0, $foundRelated->getScore());
    }

    public function testUnauthorizedAccess(): void
    {
        $client = self::createClientWithDatabase();
        $client->catchExceptions(true);

        $client->request('GET', '/admin/product-recommend/related');
        $this->assertTrue(
            $client->getResponse()->isRedirect() || 403 === $client->getResponse()->getStatusCode()
        );
    }
}
