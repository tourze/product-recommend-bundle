<?php

namespace ProductRecommendBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use ProductRecommendBundle\Entity\RelatedRecommend;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(RelatedRecommend::class)]
final class RelatedRecommendTest extends AbstractEntityTestCase
{
    protected function createEntity(): RelatedRecommend
    {
        return new RelatedRecommend();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'visitSpuId' => ['visitSpuId', '123456789'];
        yield 'recommendSpuId' => ['recommendSpuId', '987654321'];
        yield 'scene' => ['scene', '商品详情页'];
        yield 'valid' => ['valid', true];
        yield 'textReason' => ['textReason', '相似商品推荐'];
        yield 'targetGroup' => ['targetGroup', 'VIP用户'];
        yield 'score' => ['score', 8.5];
    }

    public function testCreateRelatedRecommend(): void
    {
        $related = new RelatedRecommend();

        $this->assertInstanceOf(RelatedRecommend::class, $related);
        $this->assertNull($related->getVisitSpuId());
        $this->assertNull($related->getRecommendSpuId());
        $this->assertNull($related->getScene());
        $this->assertFalse($related->isValid());
    }

    public function testSetAndGetVisitSpuId(): void
    {
        $related = new RelatedRecommend();
        $spuId = '123456789';

        $related->setVisitSpuId($spuId);

        $this->assertSame($spuId, $related->getVisitSpuId());
    }

    public function testSetAndGetRecommendSpuId(): void
    {
        $related = new RelatedRecommend();
        $spuId = '987654321';

        $related->setRecommendSpuId($spuId);

        $this->assertSame($spuId, $related->getRecommendSpuId());
    }

    public function testSetAndGetScene(): void
    {
        $related = new RelatedRecommend();
        $scene = '商品详情页';

        $related->setScene($scene);

        $this->assertSame($scene, $related->getScene());
    }

    public function testSetAndGetValid(): void
    {
        $related = new RelatedRecommend();

        $related->setValid(true);

        $this->assertTrue($related->isValid());
    }

    public function testSetAndGetTextReason(): void
    {
        $related = new RelatedRecommend();
        $reason = '相似商品推荐';

        $related->setTextReason($reason);

        $this->assertSame($reason, $related->getTextReason());
    }

    public function testSetAndGetTargetGroup(): void
    {
        $related = new RelatedRecommend();
        $group = 'VIP用户';

        $related->setTargetGroup($group);

        $this->assertSame($group, $related->getTargetGroup());
    }

    public function testSetAndGetScore(): void
    {
        $related = new RelatedRecommend();
        $score = 8.5;

        $related->setScore($score);

        $this->assertSame($score, $related->getScore());
    }
}
