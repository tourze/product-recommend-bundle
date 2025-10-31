<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ProductRecommendBundle\Repository\RecommendBlockRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

#[ORM\Table(name: 'product_recommend_block', options: ['comment' => '推荐位管理'])]
#[ORM\Entity(repositoryClass: RecommendBlockRepository::class)]
class RecommendBlock implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    #[ORM\Column(type: Types::STRING, length: 64, unique: true, options: ['comment' => '标题'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 64)]
    private ?string $title = null;

    #[ORM\Column(length: 100, nullable: true, options: ['comment' => '副标题'])]
    #[Assert\Length(max: 100)]
    private ?string $subtitle = null;

    /**
     * @var Collection<int, RecommendElement>
     */
    #[ORM\OneToMany(mappedBy: 'block', targetEntity: RecommendElement::class, fetch: 'EXTRA_LAZY', orphanRemoval: true)]
    private Collection $elements;

    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }

    public function __toString(): string
    {
        if (null === $this->getId()) {
            return '';
        }

        return (string) $this->getTitle();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
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

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): void
    {
        $this->subtitle = $subtitle;
    }

    /**
     * @return Collection<int, RecommendElement>
     */
    public function getElements(): Collection
    {
        return $this->elements;
    }

    public function addElement(RecommendElement $element): self
    {
        if (!$this->elements->contains($element)) {
            $this->elements->add($element);
            $element->setBlock($this);
        }

        return $this;
    }

    public function removeElement(RecommendElement $element): self
    {
        if ($this->elements->removeElement($element)) {
            // set the owning side to null (unless already changed)
            if ($element->getBlock() === $this) {
                $element->setBlock(null);
            }
        }

        return $this;
    }
}
