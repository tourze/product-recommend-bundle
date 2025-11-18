<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ProductRecommendBundle\Repository\RelatedRecommendRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineHelper\SortableTrait;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

/**
 * 产品间推荐、相关推荐
 *
 * 一般要解决的是"A 产品在 B 场景，需要推荐 C 产品"这种需求。
 */
#[ORM\Table(name: 'ims_recommend_goods_entity', options: ['comment' => '产品间推荐'])]
#[ORM\UniqueConstraint(name: 'ims_recommend_goods_entity_idx_uniq', columns: ['visit_spu_id', 'scene', 'recommend_spu_id'])]
#[ORM\Entity(repositoryClass: RelatedRecommendRepository::class)]
class RelatedRecommend implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;
    use SortableTrait;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    // use RemarkableAware; // Trait not found
    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '访问SPU'])]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d+$/')]
    #[Assert\Length(max: 20)]
    private ?string $visitSpuId = null;

    #[ORM\Column(length: 100, options: ['comment' => '场景'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 100)]
    private ?string $scene = null;

    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '推荐SPU'])]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d+$/')]
    #[Assert\Length(max: 20)]
    private ?string $recommendSpuId = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '推荐原因'])]
    #[Assert\Length(max: 500)]
    private ?string $textReason = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '目标分组'])]
    #[Assert\Length(max: 100)]
    private ?string $targetGroup = null;

    #[IndexColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '分数', 'default' => 1])]
    #[Assert\Type(type: 'float')]
    #[Assert\Range(min: 0, max: 100)]
    private ?float $score = null;

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getVisitSpuId(): ?string
    {
        return $this->visitSpuId;
    }

    public function setVisitSpuId(string $visitSpuId): void
    {
        $this->visitSpuId = $visitSpuId;
    }

    public function getRecommendSpuId(): ?string
    {
        return $this->recommendSpuId;
    }

    public function setRecommendSpuId(string $recommendSpuId): void
    {
        $this->recommendSpuId = $recommendSpuId;
    }

    public function getScene(): ?string
    {
        return $this->scene;
    }

    public function setScene(string $scene): void
    {
        $this->scene = $scene;
    }

    public function getTextReason(): ?string
    {
        return $this->textReason;
    }

    public function setTextReason(?string $textReason): void
    {
        $this->textReason = $textReason;
    }

    public function getTargetGroup(): ?string
    {
        return $this->targetGroup;
    }

    public function setTargetGroup(?string $targetGroup): void
    {
        $this->targetGroup = $targetGroup;
    }

    public function getScore(): ?float
    {
        return $this->score;
    }

    public function setScore(?float $score): void
    {
        $this->score = $score;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
