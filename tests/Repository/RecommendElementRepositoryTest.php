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
#[CoversClass(RecommendElementRepository::class)]
#[RunTestsInSeparateProcesses]
final class RecommendElementRepositoryTest extends AbstractRepositoryTestCase
{
    private RecommendElementRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(RecommendElementRepository::class);
    }

    protected function createNewEntity(): object
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block ' . uniqid());
        $block->setValid(true);
        self::getEntityManager()->persist($block);

        $entity = new RecommendElement();
        $entity->setSpuId('test-spu-' . uniqid());
        $entity->setBlock($block);
        $entity->setValid(true);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<RecommendElement>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }

    public function testFindOneByWithOrderByShouldReturnFirstEntity(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element1 = new RecommendElement();
        $element1->setSpuId('zzz');
        $element1->setBlock($block);
        $element1->setValid(true);

        $element2 = new RecommendElement();
        $element2->setSpuId('aaa');
        $element2->setBlock($block);
        $element2->setValid(true);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element1);
        $this->repository->save($element2);
        $this->repository->flush();

        $result = $this->repository->findOneBy(['valid' => true], ['spuId' => 'ASC']);

        $this->assertInstanceOf(RecommendElement::class, $result);
        $this->assertEquals('aaa', $result->getSpuId());
    }

    public function testQueryWithNullValues(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element1 = new RecommendElement();
        $element1->setSpuId('123');
        $element1->setBlock($block);
        $element1->setValid(true);
        $element1->setTextReason('reason');

        $element2 = new RecommendElement();
        $element2->setSpuId('456');
        $element2->setBlock($block);
        $element2->setValid(true);
        $element2->setTextReason(null);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element1);
        $this->repository->save($element2);
        $this->repository->flush();

        $result = $this->repository->findBy(['textReason' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('456', $result[0]->getSpuId());
    }

    public function testCountWithNullValuesCriteria(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element1 = new RecommendElement();
        $element1->setSpuId('123');
        $element1->setBlock($block);
        $element1->setValid(true);
        $element1->setTextReason('reason');

        $element2 = new RecommendElement();
        $element2->setSpuId('456');
        $element2->setBlock($block);
        $element2->setValid(true);
        $element2->setTextReason(null);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element1);
        $this->repository->save($element2);
        $this->repository->flush();

        $count = $this->repository->count(['textReason' => null]);

        $this->assertEquals(1, $count);
    }

    public function testSaveEntity(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element = new RecommendElement();
        $element->setSpuId('123');
        $element->setBlock($block);
        $element->setValid(true);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element);
        $this->repository->flush();

        $this->assertNotNull($element->getId());

        $found = $this->repository->find($element->getId());
        $this->assertInstanceOf(RecommendElement::class, $found);
        $this->assertEquals('123', $found->getSpuId());
    }

    public function testRemoveEntity(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element = new RecommendElement();
        $element->setSpuId('123');
        $element->setBlock($block);
        $element->setValid(true);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element);
        $this->repository->flush();
        $elementId = $element->getId();

        $this->repository->remove($element);
        $this->repository->flush();

        $found = $this->repository->find($elementId);
        $this->assertNull($found);
    }

    public function testSaveAllEntities(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element1 = new RecommendElement();
        $element1->setSpuId('123');
        $element1->setBlock($block);
        $element1->setValid(true);

        $element2 = new RecommendElement();
        $element2->setSpuId('456');
        $element2->setBlock($block);
        $element2->setValid(false);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->saveAll([$element1, $element2]);
        $this->repository->flush();

        $this->assertNotNull($element1->getId());
        $this->assertNotNull($element2->getId());
    }

    public function testClearRepository(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element = new RecommendElement();
        $element->setSpuId('123');
        $element->setBlock($block);
        $element->setValid(true);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element);
        $this->repository->flush();
        $elementId = $element->getId();

        $this->repository->clear();

        $foundElement = $this->repository->find($elementId);
        $this->assertInstanceOf(RecommendElement::class, $foundElement);
        $this->assertEquals('123', $foundElement->getSpuId());
    }

    public function testFlushRepository(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element = new RecommendElement();
        $element->setSpuId('123');
        $element->setBlock($block);
        $element->setValid(true);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element);
        $this->repository->flush();

        $this->assertNotNull($element->getId());
    }

    public function testFindOneByWithMultipleNullFieldsShouldReturnCorrectEntity(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element1 = new RecommendElement();
        $element1->setSpuId('111');
        $element1->setBlock($block);
        $element1->setValid(true);
        $element1->setTextReason('Has reason');
        $element1->setTargetGroup('Group A');
        $element1->setScore(5.0);

        $element2 = new RecommendElement();
        $element2->setSpuId('222');
        $element2->setBlock($block);
        $element2->setValid(true);
        $element2->setTextReason(null);
        $element2->setTargetGroup(null);
        $element2->setScore(null);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element1);
        $this->repository->save($element2);
        $this->repository->flush();

        $result = $this->repository->findOneBy(['textReason' => null, 'targetGroup' => null]);

        $this->assertInstanceOf(RecommendElement::class, $result);
        $this->assertEquals('222', $result->getSpuId());
        $this->assertNull($result->getTextReason());
        $this->assertNull($result->getTargetGroup());
        $this->assertNull($result->getScore());
    }

    public function testFindOneByWithManyToManyRelationShouldReturnCorrectEntity(): void
    {
        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $tag1 = new RecommendElementTag();
        $tag1->setTitle('Tag 1');
        $tag1->setValid(true);

        $tag2 = new RecommendElementTag();
        $tag2->setTitle('Tag 2');
        $tag2->setValid(true);

        $element1 = new RecommendElement();
        $element1->setSpuId('111');
        $element1->setBlock($block);
        $element1->setValid(true);

        $element2 = new RecommendElement();
        $element2->setSpuId('222');
        $element2->setBlock($block);
        $element2->setValid(true);

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $tagRepository = self::getService(RecommendElementTagRepository::class);
        $blockRepository->save($block);
        $tagRepository->save($tag1);
        $tagRepository->save($tag2);
        $this->repository->save($element1);
        $this->repository->save($element2);
        $this->repository->flush();

        // 现在添加关联关系
        $element1->addRecommendElementTag($tag1);
        $element2->addRecommendElementTag($tag1);
        $element2->addRecommendElementTag($tag2);
        $this->repository->flush();

        // 通过关联查询找到有特定标签的元素
        $result = $this->repository->findOneBy(['spuId' => '222']);

        $this->assertInstanceOf(RecommendElement::class, $result);
        $this->assertEquals('222', $result->getSpuId());
        $this->assertCount(2, $result->getRecommendElementTags());
        $this->assertTrue($result->getRecommendElementTags()->contains($tag1));
        $this->assertTrue($result->getRecommendElementTags()->contains($tag2));
    }

    public function testFindOneByWithComplexOrderByShouldReturnCorrectEntity(): void
    {
        // 清理现有数据
        $existingEntities = $this->repository->findAll();
        foreach ($existingEntities as $existingEntity) {
            $this->repository->remove($existingEntity);
        }
        $this->repository->flush();

        $block = new RecommendBlock();
        $block->setTitle('Test Block');
        $block->setValid(true);

        $element1 = new RecommendElement();
        $element1->setSpuId('333');
        $element1->setBlock($block);
        $element1->setValid(true);
        $element1->setScore(3.0);
        $element1->setTextReason('Reason C');

        $element2 = new RecommendElement();
        $element2->setSpuId('111');
        $element2->setBlock($block);
        $element2->setValid(true);
        $element2->setScore(1.0);
        $element2->setTextReason('Reason A');

        $element3 = new RecommendElement();
        $element3->setSpuId('222');
        $element3->setBlock($block);
        $element3->setValid(true);
        $element3->setScore(2.0);
        $element3->setTextReason('Reason B');

        $blockRepository = self::getService(RecommendBlockRepository::class);
        $blockRepository->save($block);
        $this->repository->save($element1);
        $this->repository->save($element2);
        $this->repository->save($element3);
        $this->repository->flush();

        // 按score降序，spuId升序
        $result = $this->repository->findOneBy(['valid' => true], ['score' => 'DESC', 'spuId' => 'ASC']);

        $this->assertInstanceOf(RecommendElement::class, $result);
        $this->assertEquals('333', $result->getSpuId());
        $this->assertEquals(3.0, $result->getScore());
    }
}
