<?php

namespace ProductRecommendBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ProductRecommendBundle\Entity\RecommendElement;
use ProductRecommendBundle\Entity\RecommendElementTag;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(RecommendElementTag::class)]
final class RecommendElementTagTest extends AbstractEntityTestCase
{
    protected function createEntity(): RecommendElementTag
    {
        return new RecommendElementTag();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'title' => ['title', '热门标签'];
        yield 'valid' => ['valid', true];
    }

    public function testCreateRecommendElementTag(): void
    {
        $tag = new RecommendElementTag();

        $this->assertInstanceOf(RecommendElementTag::class, $tag);
        $this->assertNull($tag->getTitle());
        $this->assertFalse($tag->isValid());
    }

    public function testSetAndGetTitle(): void
    {
        $tag = new RecommendElementTag();
        $title = '热门标签';

        $tag->setTitle($title);

        $this->assertSame($title, $tag->getTitle());
    }

    public function testSetAndGetValid(): void
    {
        $tag = new RecommendElementTag();

        $tag->setValid(true);

        $this->assertTrue($tag->isValid());
    }

    public function testAddAndRemoveRecommendElement(): void
    {
        $tag = new RecommendElementTag();
        $element = new RecommendElement();

        $tag->addRecommendElement($element);

        $this->assertTrue($tag->getRecommendElements()->contains($element));
        $this->assertTrue($element->getRecommendElementTags()->contains($tag));

        $tag->removeRecommendElement($element);

        $this->assertFalse($tag->getRecommendElements()->contains($element));
    }

    public function testToString(): void
    {
        $tag = new RecommendElementTag();

        // 当 ID 为 null 或 0 时
        $this->assertSame('', (string) $tag);

        // 设置标题后仍为空（因为 ID 仍为 0）
        $tag->setTitle('测试标签');
        $this->assertSame('', (string) $tag);
    }
}
