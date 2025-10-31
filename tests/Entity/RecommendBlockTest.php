<?php

namespace ProductRecommendBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Entity\RecommendElement;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(RecommendBlock::class)]
final class RecommendBlockTest extends AbstractEntityTestCase
{
    protected function createEntity(): RecommendBlock
    {
        return new RecommendBlock();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'title' => ['title', '测试标题'];
        yield 'subtitle' => ['subtitle', '副标题'];
        yield 'valid' => ['valid', true];
    }

    public function testCreateRecommendBlock(): void
    {
        $block = new RecommendBlock();

        $this->assertInstanceOf(RecommendBlock::class, $block);
        $this->assertNull($block->getTitle());
        $this->assertNull($block->getSubtitle());
        $this->assertFalse($block->isValid());
    }

    public function testSetAndGetTitle(): void
    {
        $block = new RecommendBlock();
        $title = '测试推荐位';

        $block->setTitle($title);

        $this->assertSame($title, $block->getTitle());
    }

    public function testSetAndGetSubtitle(): void
    {
        $block = new RecommendBlock();
        $subtitle = '副标题';

        $block->setSubtitle($subtitle);

        $this->assertSame($subtitle, $block->getSubtitle());
    }

    public function testSetAndGetValid(): void
    {
        $block = new RecommendBlock();

        $block->setValid(true);

        $this->assertTrue($block->isValid());
    }

    public function testAddAndRemoveElement(): void
    {
        $block = new RecommendBlock();
        $element = new RecommendElement();

        $block->addElement($element);

        $this->assertTrue($block->getElements()->contains($element));
        $this->assertSame($block, $element->getBlock());

        $block->removeElement($element);

        $this->assertFalse($block->getElements()->contains($element));
    }

    public function testToString(): void
    {
        $block = new RecommendBlock();

        // 当 ID 为 null 时
        $this->assertSame('', (string) $block);

        // 设置标题但 ID 仍为空时，toString 仍返回空字符串
        $block->setTitle('测试标题');
        $this->assertSame('', (string) $block);
    }
}
