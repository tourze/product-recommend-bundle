<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ProductRecommendBundle\Repository\RecommendElementTagRepository;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;

#[ORM\Table(name: 'product_recommend_element_tag', options: ['comment' => '推荐位商品标签'])]
#[ORM\Entity(repositoryClass: RecommendElementTagRepository::class)]
class RecommendElementTag implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    #[ORM\Column(type: Types::STRING, length: 60, options: ['comment' => '标签名'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 60)]
    private ?string $title = null;

    /**
     * @var Collection<int, RecommendElement>
     */
    #[Ignore]
    #[ORM\ManyToMany(targetEntity: RecommendElement::class, inversedBy: 'recommendElementTags', fetch: 'EXTRA_LAZY')]
    private Collection $elements;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

    public function __toString(): string
    {
        if (0 === $this->getId()) {
            return '';
        }

        return (string) $this->getTitle();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * @return Collection<int, RecommendElement>
     */
    public function getRecommendElements(): Collection
    {
        return $this->elements;
    }

    public function addRecommendElement(RecommendElement $element): self
    {
        if (!$this->elements->contains($element)) {
            $this->elements->add($element);
            $element->addRecommendElementTag($this);
        }

        return $this;
    }

    public function removeRecommendElement(RecommendElement $element): self
    {
        if ($this->elements->removeElement($element)) {
            $element->removeRecommendElementTag($this);
        }

        return $this;
    }
}
