<?php

namespace ProductRecommendBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Repository\RecommendBlockRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(RecommendBlockRepository::class)]
#[RunTestsInSeparateProcesses]
final class RecommendBlockRepositoryTest extends AbstractRepositoryTestCase
{
    private RecommendBlockRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(RecommendBlockRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new RecommendBlock();
        $entity->setTitle('Test Block ' . uniqid());
        $entity->setValid(true);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<RecommendBlock>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    public function testFindOneByWithOrderByShouldReturnFirstEntity(): void
    {
        $entity1 = new RecommendBlock();
        $entity1->setTitle('ZZZ Block');
        $entity1->setValid(true);

        $entity2 = new RecommendBlock();
        $entity2->setTitle('AAA Block');
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $result = $this->repository->findOneBy(['valid' => true], ['title' => 'ASC']);

        $this->assertInstanceOf(RecommendBlock::class, $result);
        $this->assertEquals('AAA Block', $result->getTitle());
    }

    public function testQueryWithNullValues(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RecommendBlock();
        $entity1->setTitle('Block with subtitle');
        $entity1->setSubtitle('Test subtitle');
        $entity1->setValid(true);

        $entity2 = new RecommendBlock();
        $entity2->setTitle('Block without subtitle');
        $entity2->setSubtitle(null);
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $result = $this->repository->findBy(['subtitle' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Block without subtitle', $result[0]->getTitle());
    }

    public function testCountWithNullValuesCriteria(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RecommendBlock();
        $entity1->setTitle('Block with subtitle');
        $entity1->setSubtitle('Test subtitle');
        $entity1->setValid(true);

        $entity2 = new RecommendBlock();
        $entity2->setTitle('Block without subtitle');
        $entity2->setSubtitle(null);
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $count = $this->repository->count(['subtitle' => null]);

        $this->assertEquals(1, $count);
    }

    public function testSaveEntity(): void
    {
        $entity = new RecommendBlock();
        $entity->setTitle('Test Save');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();

        $this->assertNotNull($entity->getId());

        $found = $this->repository->find($entity->getId());
        $this->assertInstanceOf(RecommendBlock::class, $found);
        $this->assertEquals('Test Save', $found->getTitle());
    }

    public function testRemoveEntity(): void
    {
        $entity = new RecommendBlock();
        $entity->setTitle('Test Remove');
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
        $entity1 = new RecommendBlock();
        $entity1->setTitle('Batch Save 1');
        $entity1->setValid(true);

        $entity2 = new RecommendBlock();
        $entity2->setTitle('Batch Save 2');
        $entity2->setValid(false);

        $this->repository->saveAll([$entity1, $entity2]);
        $this->repository->flush();

        $this->assertNotNull($entity1->getId());
        $this->assertNotNull($entity2->getId());
    }

    public function testClearRepository(): void
    {
        $entity = new RecommendBlock();
        $entity->setTitle('Test Clear');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();
        $entityId = $entity->getId();

        $this->repository->clear();

        $foundEntity = $this->repository->find($entityId);
        $this->assertInstanceOf(RecommendBlock::class, $foundEntity);
        $this->assertEquals('Test Clear', $foundEntity->getTitle());
    }

    public function testFlushRepository(): void
    {
        $entity = new RecommendBlock();
        $entity->setTitle('Test Flush');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();

        $this->assertNotNull($entity->getId());
    }

    public function testFindOneByWithComplexOrderByShouldReturnCorrectEntity(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RecommendBlock();
        $entity1->setTitle('C Block');
        $entity1->setSubtitle('B Subtitle');
        $entity1->setValid(true);

        $entity2 = new RecommendBlock();
        $entity2->setTitle('A Block');
        $entity2->setSubtitle('A Subtitle');
        $entity2->setValid(true);

        $entity3 = new RecommendBlock();
        $entity3->setTitle('B Block');
        $entity3->setSubtitle('C Subtitle');
        $entity3->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->save($entity3);
        $this->repository->flush();

        // 按title降序，subtitle升序
        $result = $this->repository->findOneBy(['valid' => true], ['title' => 'DESC', 'subtitle' => 'ASC']);

        $this->assertInstanceOf(RecommendBlock::class, $result);
        $this->assertEquals('C Block', $result->getTitle());
        $this->assertEquals('B Subtitle', $result->getSubtitle());
    }

    public function testFindOneByWithNullValueInOrderByShouldHandleCorrectly(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RecommendBlock();
        $entity1->setTitle('A Block');
        $entity1->setSubtitle('A Subtitle');
        $entity1->setValid(true);

        $entity2 = new RecommendBlock();
        $entity2->setTitle('B Block');
        $entity2->setSubtitle('B Subtitle');
        $entity2->setValid(true);

        $entity3 = new RecommendBlock();
        $entity3->setTitle('C Block');
        $entity3->setSubtitle(null);
        $entity3->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->save($entity3);
        $this->repository->flush();

        // 测试排序能够正常工作，不关心NULL值的具体位置
        $result = $this->repository->findOneBy(['valid' => true], ['title' => 'ASC']);

        $this->assertInstanceOf(RecommendBlock::class, $result);
        $this->assertEquals('A Block', $result->getTitle());
        $this->assertEquals('A Subtitle', $result->getSubtitle());

        // 测试按subtitle排序也能正常工作
        $resultBySubtitle = $this->repository->findOneBy(['valid' => true], ['subtitle' => 'ASC']);
        $this->assertInstanceOf(RecommendBlock::class, $resultBySubtitle);
        $this->assertNotNull($resultBySubtitle->getTitle());
    }
}
