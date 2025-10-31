<?php

namespace ProductRecommendBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Entity\RecommendElement;
use ProductRecommendBundle\Entity\RecommendElementTag;
use ProductRecommendBundle\Repository\RecommendBlockRepository;
use ProductRecommendBundle\Repository\RecommendElementRepository;
use ProductRecommendBundle\Repository\RecommendElementTagRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(RecommendElementTagRepository::class)]
#[RunTestsInSeparateProcesses]
final class RecommendElementTagRepositoryTest extends AbstractRepositoryTestCase
{
    private RecommendElementTagRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(RecommendElementTagRepository::class);
    }

    protected function createNewEntity(): object
    {
        $entity = new RecommendElementTag();
        $entity->setTitle('Test Tag ' . uniqid());
        $entity->setValid(true);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<RecommendElementTag>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    public function testFindOneByWithOrderByShouldReturnFirstEntity(): void
    {
        $entity1 = new RecommendElementTag();
        $entity1->setTitle('ZZZ Tag');
        $entity1->setValid(true);

        $entity2 = new RecommendElementTag();
        $entity2->setTitle('AAA Tag');
        $entity2->setValid(true);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $result = $this->repository->findOneBy(['valid' => true], ['title' => 'ASC']);

        $this->assertInstanceOf(RecommendElementTag::class, $result);
        $this->assertEquals('AAA Tag', $result->getTitle());
    }

    public function testQueryWithNullValues(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RecommendElementTag();
        $entity1->setTitle('Tag with valid');
        $entity1->setValid(true);

        $entity2 = new RecommendElementTag();
        $entity2->setTitle('Tag without valid');
        $entity2->setValid(null);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $result = $this->repository->findBy(['valid' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Tag without valid', $result[0]->getTitle());
    }

    public function testCountWithNullValuesCriteria(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $entity1 = new RecommendElementTag();
        $entity1->setTitle('Tag with valid');
        $entity1->setValid(true);

        $entity2 = new RecommendElementTag();
        $entity2->setTitle('Tag without valid');
        $entity2->setValid(null);

        $this->repository->save($entity1);
        $this->repository->save($entity2);
        $this->repository->flush();

        $count = $this->repository->count(['valid' => null]);

        $this->assertEquals(1, $count);
    }

    public function testSaveEntity(): void
    {
        $entity = new RecommendElementTag();
        $entity->setTitle('Test Save');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();

        $this->assertNotNull($entity->getId());

        $found = $this->repository->find($entity->getId());
        $this->assertInstanceOf(RecommendElementTag::class, $found);
        $this->assertEquals('Test Save', $found->getTitle());
    }

    public function testRemoveEntity(): void
    {
        $entity = new RecommendElementTag();
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
        $entity1 = new RecommendElementTag();
        $entity1->setTitle('Batch Save 1');
        $entity1->setValid(true);

        $entity2 = new RecommendElementTag();
        $entity2->setTitle('Batch Save 2');
        $entity2->setValid(false);

        $this->repository->saveAll([$entity1, $entity2]);
        $this->repository->flush();

        $this->assertNotNull($entity1->getId());
        $this->assertNotNull($entity2->getId());
    }

    public function testClearRepository(): void
    {
        $entity = new RecommendElementTag();
        $entity->setTitle('Test Clear');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();
        $entityId = $entity->getId();

        $this->repository->clear();

        $foundEntity = $this->repository->find($entityId);
        $this->assertInstanceOf(RecommendElementTag::class, $foundEntity);
        $this->assertEquals('Test Clear', $foundEntity->getTitle());
    }

    public function testFlushRepository(): void
    {
        $entity = new RecommendElementTag();
        $entity->setTitle('Test Flush');
        $entity->setValid(true);

        $this->repository->save($entity);
        $this->repository->flush();

        $this->assertNotNull($entity->getId());
    }

    public function testFindOneByWithManyToManyRelationShouldReturnCorrectEntity(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $tag = new RecommendElementTag();
        $tag->setTitle('Test Tag');
        $tag->setValid(true);

        $element1 = new RecommendElement();
        $element1->setSpuId('111');
        $element1->setBlock($block);
        $element1->setValid(true);

        $element2 = new RecommendElement();
        $element2->setSpuId('222');
        $element2->setBlock($block);
        $element2->setValid(true);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $elementRepository = self::getService(RecommendElementRepository::class);
        $blockRepository->save($block);
        $this->repository->save($tag);
        $elementRepository->save($element1);
        $elementRepository->save($element2);
        $this->repository->flush();

        // 现在添加关联关系
        $element1->addRecommendElementTag($tag);
        $element2->addRecommendElementTag($tag);
        $this->repository->flush();

        // 通过标签查询，验证关联关系
        $result = $this->repository->findOneBy(['title' => 'Test Tag']);

        $this->assertInstanceOf(RecommendElementTag::class, $result);
        $this->assertEquals('Test Tag', $result->getTitle());
        $this->assertCount(2, $result->getRecommendElements());
        $this->assertTrue($result->getRecommendElements()->contains($element1));
        $this->assertTrue($result->getRecommendElements()->contains($element2));
    }

    public function testFindOneByWithNullValidFieldShouldReturnCorrectEntity(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $tag1 = new RecommendElementTag();
        $tag1->setTitle('Valid Tag');
        $tag1->setValid(true);

        $tag2 = new RecommendElementTag();
        $tag2->setTitle('Null Valid Tag');
        $tag2->setValid(null);

        $this->repository->save($tag1);
        $this->repository->save($tag2);
        $this->repository->flush();

        $result = $this->repository->findOneBy(['valid' => null]);

        $this->assertInstanceOf(RecommendElementTag::class, $result);
        $this->assertEquals('Null Valid Tag', $result->getTitle());
        $this->assertNull($result->isValid());
    }

    public function testFindOneByWithComplexOrderByShouldReturnCorrectEntity(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $tag1 = new RecommendElementTag();
        $tag1->setTitle('B Tag');
        $tag1->setValid(true);

        $tag2 = new RecommendElementTag();
        $tag2->setTitle('A Tag');
        $tag2->setValid(true);

        $tag3 = new RecommendElementTag();
        $tag3->setTitle('C Tag');
        $tag3->setValid(false);

        $this->repository->save($tag1);
        $this->repository->save($tag2);
        $this->repository->save($tag3);
        $this->repository->flush();

        // 按title降序，valid升序
        $result = $this->repository->findOneBy([], ['title' => 'DESC', 'valid' => 'ASC']);

        $this->assertInstanceOf(RecommendElementTag::class, $result);
        $this->assertEquals('C Tag', $result->getTitle());
        $this->assertFalse($result->isValid());
    }
}
