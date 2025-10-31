<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ProductRecommendBundle\Repository\RecommendElementRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineHelper\SortableTrait;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

// use DoctrineEnhanceBundle\Traits\RemarkableAware; // Trait not found

#[ORM\Table(name: 'product_recommend_element', options: ['comment' => '推荐位商品'])]
#[ORM\UniqueConstraint(name: 'product_recommend_element_idx_uniq', columns: ['block_id', 'spu_id'])]
#[ORM\Entity(repositoryClass: RecommendElementRepository::class)]
class RecommendElement implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    // use RemarkableAware; // Trait not found
    use SortableTrait;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    #[ORM\ManyToOne(inversedBy: 'elements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RecommendBlock $block = null;

    #[ORM\Column(type: Types::STRING, length: 20, options: ['comment' => '商品ID'])]
    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^\d+$/')]
    #[Assert\Length(max: 20)]
    private ?string $spuId = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true, options: ['comment' => '图片'])]
    #[Assert\Length(max: 255)]
    #[Assert\Url(message: '请输入有效的图片地址')]
    private ?string $thumb = null;

    #[ORM\Column(type: Types::TEXT, nullable: true, options: ['comment' => '推荐理由'])]
    #[Assert\Length(max: 500)]
    private ?string $textReason = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '目标分组'])]
    #[Assert\Length(max: 100)]
    private ?string $targetGroup = null;

    #[IndexColumn]
    #[ORM\Column(nullable: true, options: ['comment' => '分数', 'default' => 1])]
    #[Assert\Type(type: 'float')]
    #[Assert\Range(min: 0, max: 100)]
    private ?float $score = 1;

    /**
     * @var Collection<int, RecommendElementTag>
     */
    #[ORM\ManyToMany(targetEntity: RecommendElementTag::class, inversedBy: 'elements', fetch: 'EXTRA_LAZY')]
    private Collection $recommendElementTags;

    public function __construct()
    {
        $this->recommendElementTags = new ArrayCollection();
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getBlock(): ?RecommendBlock
    {
        return $this->block;
    }

    public function setBlock(?RecommendBlock $block): void
    {
        $this->block = $block;
    }

    public function getSpuId(): ?string
    {
        return $this->spuId;
    }

    public function setSpuId(string $spuId): void
    {
        $this->spuId = $spuId;
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

    /**
     * @return Collection<int, RecommendElementTag>
     */
    public function getRecommendElementTags(): Collection
    {
        return $this->recommendElementTags;
    }

    public function addRecommendElementTag(RecommendElementTag $elementTag): self
    {
        if (!$this->recommendElementTags->contains($elementTag)) {
            $this->recommendElementTags->add($elementTag);
            $elementTag->addRecommendElement($this);
        }

        return $this;
    }

    public function removeRecommendElementTag(RecommendElementTag $elementTag): self
    {
        if ($this->recommendElementTags->removeElement($elementTag)) {
            $elementTag->removeRecommendElement($this);
        }

        return $this;
    }

    public function getThumb(): ?string
    {
        return $this->thumb;
    }

    public function setThumb(?string $thumb): void
    {
        $this->thumb = $thumb;
    }

    public function __toString(): string
    {
        return (string) $this->getId();
    }
}
