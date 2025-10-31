<?php

declare(strict_types=1);

namespace ProductRecommendBundle;

use Knp\Menu\ItemInterface;
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Entity\RecommendElement;
use ProductRecommendBundle\Entity\RecommendElementTag;
use ProductRecommendBundle\Entity\RelatedRecommend;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('商品推荐')) {
            $item->addChild('商品推荐');
        }

        $productRecommendItem = $item->getChild('商品推荐');
        if (null !== $productRecommendItem) {
            $productRecommendItem->addChild('推荐位管理')->setUri($this->linkGenerator->getCurdListPage(RecommendBlock::class));
            $productRecommendItem->addChild('推荐元素管理')->setUri($this->linkGenerator->getCurdListPage(RecommendElement::class));
            $productRecommendItem->addChild('推荐元素标签管理')->setUri($this->linkGenerator->getCurdListPage(RecommendElementTag::class));
            $productRecommendItem->addChild('相关推荐管理')->setUri($this->linkGenerator->getCurdListPage(RelatedRecommend::class));
        }
    }
}
