<?php

namespace ProductRecommendBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Entity\RecommendElement;
use ProductRecommendBundle\Entity\RecommendElementTag;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(RecommendElement::class)]
final class RecommendElementTest extends AbstractEntityTestCase
{
    protected function createEntity(): RecommendElement
    {
        return new RecommendElement();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'spuId' => ['spuId', '123456789'];
        yield 'thumb' => ['thumb', '/path/to/image.jpg'];
        yield 'valid' => ['valid', true];
        yield 'textReason' => ['textReason', '热门推荐'];
        yield 'score' => ['score', 9.5];
    }

    public function testCreateRecommendElement(): void
    {
        $element = new RecommendElement();

        $this->assertInstanceOf(RecommendElement::class, $element);
        $this->assertNull($element->getSpuId());
        $this->assertNull($element->getThumb());
        $this->assertFalse($element->isValid());
        $this->assertSame(1.0, $element->getScore());
    }

    public function testSetAndGetSpuId(): void
    {
        $element = new RecommendElement();
        $spuId = '123456789';

        $element->setSpuId($spuId);

        $this->assertSame($spuId, $element->getSpuId());
    }

    public function testSetAndGetBlock(): void
    {
        $element = new RecommendElement();
        $block = new RecommendBlock();

        $element->setBlock($block);

        $this->assertSame($block, $element->getBlock());
    }

    public function testSetAndGetValid(): void
    {
        $element = new RecommendElement();

        $element->setValid(true);

        $this->assertTrue($element->isValid());
    }

    public function testSetAndGetTextReason(): void
    {
        $element = new RecommendElement();
        $reason = '热门推荐';

        $element->setTextReason($reason);

        $this->assertSame($reason, $element->getTextReason());
    }

    public function testSetAndGetScore(): void
    {
        $element = new RecommendElement();
        $score = 9.5;

        $element->setScore($score);

        $this->assertSame($score, $element->getScore());
    }

    public function testAddAndRemoveRecommendElementTag(): void
    {
        $element = new RecommendElement();
        $tag = new RecommendElementTag();

        $element->addRecommendElementTag($tag);

        $this->assertTrue($element->getRecommendElementTags()->contains($tag));

        $element->removeRecommendElementTag($tag);

        $this->assertFalse($element->getRecommendElementTags()->contains($tag));
    }

    public function testSetAndGetThumb(): void
    {
        $element = new RecommendElement();
        $thumb = '/path/to/image.jpg';

        $element->setThumb($thumb);

        $this->assertSame($thumb, $element->getThumb());
    }
}
