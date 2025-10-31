<?php

namespace ProductRecommendBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\Entity\RelatedRecommend;
use ProductRecommendBundle\Repository\RelatedRecommendRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(RelatedRecommendRepository::class)]
#[RunTestsInSeparateProcesses]
final class RelatedRecommendRepositoryTest extends AbstractRepositoryTestCase
{
    private RelatedRecommendRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(RelatedRecommendRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new RelatedRecommend();

        // 设置基本字段
        $entity->setVisitSpuId('test_visit_' . uniqid());
        $entity->setScene('test_scene_' . uniqid());
        $entity->setRecommendSpuId('test_recommend_' . uniqid());
        $entity->setValid(true);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<RelatedRecommend>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    public function testFindOneByWithOrderByShouldReturnFirstEntity(): void
    {
        $entity1 = new RelatedRecommend();
        $entity1->setVisitSpuId('111111111');
        $entity1->setScene('zzz_scene');
        $entity1->setRecommendSpuId('111111112');
        $entity1->setValid(true);

        $entity2 = new RelatedRecommend();
        $entity2->setVisitSpuId('222222221');
        $entity2->setScene('aaa_scene');
        $entity2->setRecommendSpuId('222222222');
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $result = $this->repository->findOneBy(['valid' => true], ['scene' => 'ASC']);

        $this->assertInstanceOf(RelatedRecommend::class, $result);
        $this->assertEquals('aaa_scene', $result->getScene());
    }

    public function testQueryWithNullValues(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RelatedRecommend();
        $entity1->setVisitSpuId('111111111');
        $entity1->setScene('test_scene');
        $entity1->setRecommendSpuId('111111112');
        $entity1->setTextReason('Some reason');
        $entity1->setValid(true);

        $entity2 = new RelatedRecommend();
        $entity2->setVisitSpuId('222222221');
        $entity2->setScene('test_scene');
        $entity2->setRecommendSpuId('222222222');
        $entity2->setTextReason(null);
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $result = $this->repository->findBy(['textReason' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertNull($result[0]->getTextReason());
    }

    public function testCountWithNullValuesCriteria(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RelatedRecommend();
        $entity1->setVisitSpuId('111111111');
        $entity1->setScene('test_scene');
        $entity1->setRecommendSpuId('111111112');
        $entity1->setTextReason('Some reason');
        $entity1->setValid(true);

        $entity2 = new RelatedRecommend();
        $entity2->setVisitSpuId('222222221');
        $entity2->setScene('test_scene');
        $entity2->setRecommendSpuId('222222222');
        $entity2->setTextReason(null);
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $count = $this->repository->count(['textReason' => null]);

        $this->assertEquals(1, $count);
    }

    public function testSaveEntity(): void
    {
        $entity = new RelatedRecommend();
        $entity->setVisitSpuId('123456789');
        $entity->setScene('test_save');
        $entity->setRecommendSpuId('987654321');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();

        $this->assertNotNull($entity->getId());

        $found = $this->repository->find($entity->getId());
        $this->assertInstanceOf(RelatedRecommend::class, $found);
        $this->assertEquals('test_save', $found->getScene());
    }

    public function testRemoveEntity(): void
    {
        $entity = new RelatedRecommend();
        $entity->setVisitSpuId('123456789');
        $entity->setScene('test_remove');
        $entity->setRecommendSpuId('987654321');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();
        $entityId = $entity->getId();

        $this->repository->remove($entity);
        $this->repository->flush();

        $found = $this->repository->find($entityId);
        $this->assertNull($found);
    }

    public function testSaveAllEntities(): void
    {
        $entity1 = new RelatedRecommend();
        $entity1->setVisitSpuId('111111111');
        $entity1->setScene('batch_save_1');
        $entity1->setRecommendSpuId('111111112');
        $entity1->setValid(true);

        $entity2 = new RelatedRecommend();
        $entity2->setVisitSpuId('222222221');
        $entity2->setScene('batch_save_2');
        $entity2->setRecommendSpuId('222222222');
        $entity2->setValid(false);

        $this->repository->saveAll([$entity1, $entity2]);
        $this->repository->flush();

        $this->assertNotNull($entity1->getId());
        $this->assertNotNull($entity2->getId());
    }

    public function testClearRepository(): void
    {
        $entity = new RelatedRecommend();
        $entity->setVisitSpuId('123456789');
        $entity->setScene('test_clear');
        $entity->setRecommendSpuId('987654321');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();
        $entityId = $entity->getId();

        $this->repository->clear();

        $foundEntity = $this->repository->find($entityId);
        $this->assertInstanceOf(RelatedRecommend::class, $foundEntity);
        $this->assertEquals('test_clear', $foundEntity->getScene());
    }

    public function testFlushRepository(): void
    {
        $entity = new RelatedRecommend();
        $entity->setVisitSpuId('123456789');
        $entity->setScene('test_flush');
        $entity->setRecommendSpuId('987654321');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();

        $this->assertNotNull($entity->getId());
    }

    public function testFindOneByWithMultipleNullFieldsShouldReturnCorrectEntity(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RelatedRecommend();
        $entity1->setVisitSpuId('111111111');
        $entity1->setScene('test_scene');
        $entity1->setRecommendSpuId('111111112');
        $entity1->setTextReason('Has reason');
        $entity1->setTargetGroup('Group A');
        $entity1->setScore(5.0);
        $entity1->setValid(true);

        $entity2 = new RelatedRecommend();
        $entity2->setVisitSpuId('222222221');
        $entity2->setScene('test_scene');
        $entity2->setRecommendSpuId('222222222');
        $entity2->setTextReason(null);
        $entity2->setTargetGroup(null);
        $entity2->setScore(null);
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $result = $this->repository->findOneBy(['textReason' => null, 'targetGroup' => null]);

        $this->assertInstanceOf(RelatedRecommend::class, $result);
        $this->assertEquals('222222221', $result->getVisitSpuId());
        $this->assertNull($result->getTextReason());
        $this->assertNull($result->getTargetGroup());
        $this->assertNull($result->getScore());
    }

    public function testFindOneByWithAllNullFieldsShouldReturnCorrectEntity(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RelatedRecommend();
        $entity1->setVisitSpuId('111111111');
        $entity1->setScene('scene1');
        $entity1->setRecommendSpuId('111111112');
        $entity1->setTextReason('Has reason');
        $entity1->setTargetGroup('Group A');
        $entity1->setScore(5.0);
        $entity1->setValid(true);

        $entity2 = new RelatedRecommend();
        $entity2->setVisitSpuId('222222221');
        $entity2->setScene('scene2');
        $entity2->setRecommendSpuId('222222222');
        $entity2->setTextReason(null);
        $entity2->setTargetGroup(null);
        $entity2->setScore(null);
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $result = $this->repository->findOneBy(['textReason' => null, 'targetGroup' => null, 'score' => null]);

        $this->assertInstanceOf(RelatedRecommend::class, $result);
        $this->assertEquals('222222221', $result->getVisitSpuId());
        $this->assertEquals('scene2', $result->getScene());
        $this->assertNull($result->getTextReason());
        $this->assertNull($result->getTargetGroup());
        $this->assertNull($result->getScore());
    }

    public function testFindOneByWithComplexOrderByShouldReturnCorrectEntity(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RelatedRecommend();
        $entity1->setVisitSpuId('111111111');
        $entity1->setScene('C Scene');
        $entity1->setRecommendSpuId('111111112');
        $entity1->setScore(3.0);
        $entity1->setValid(true);

        $entity2 = new RelatedRecommend();
        $entity2->setVisitSpuId('222222221');
        $entity2->setScene('A Scene');
        $entity2->setRecommendSpuId('222222222');
        $entity2->setScore(1.0);
        $entity2->setValid(true);

        $entity3 = new RelatedRecommend();
        $entity3->setVisitSpuId('333333331');
        $entity3->setScene('B Scene');
        $entity3->setRecommendSpuId('333333332');
        $entity3->setScore(2.0);
        $entity3->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->save($entity3);
        $this->repository->flush();

        // 按scene降序，score升序
        $result = $this->repository->findOneBy(['valid' => true], ['scene' => 'DESC', 'score' => 'ASC']);

        $this->assertInstanceOf(RelatedRecommend::class, $result);
        $this->assertEquals('C Scene', $result->getScene());
        $this->assertEquals(3.0, $result->getScore());
    }

    public function testFindOneByWithNullValueInOrderByShouldHandleCorrectly(): void
    {
        $entity1 = new RelatedRecommend();
        $entity1->setVisitSpuId('111111111');
        $entity1->setScene('A Scene');
        $entity1->setRecommendSpuId('111111112');
        $entity1->setScore(1.0);
        $entity1->setValid(true);

        $entity2 = new RelatedRecommend();
        $entity2->setVisitSpuId('222222221');
        $entity2->setScene('B Scene');
        $entity2->setRecommendSpuId('222222222');
        $entity2->setScore(2.0);
        $entity2->setValid(true);

        $entity3 = new RelatedRecommend();
        $entity3->setVisitSpuId('333333331');
        $entity3->setScene('C Scene');
        $entity3->setRecommendSpuId('333333332');
        $entity3->setScore(null);
        $entity3->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->save($entity3);
        $this->repository->flush();

        // 测试按scene排序能够正常工作
        $result = $this->repository->findOneBy(['valid' => true], ['scene' => 'ASC']);

        $this->assertInstanceOf(RelatedRecommend::class, $result);
        $this->assertEquals('A Scene', $result->getScene());
        $this->assertEquals('111111111', $result->getVisitSpuId());

        // 测试按score排序也能正常工作，不关心NULL值的具体位置
        $resultByScore = $this->repository->findOneBy(['valid' => true], ['score' => 'ASC']);
        $this->assertInstanceOf(RelatedRecommend::class, $resultByScore);
        $this->assertNotNull($resultByScore->getVisitSpuId());
    }
}
