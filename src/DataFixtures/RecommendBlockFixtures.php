<?php

declare(strict_types=1);

namespace ProductRecommendBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ProductRecommendBundle\Entity\RecommendBlock;

class RecommendBlockFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $block1 = new RecommendBlock();
        $block1->setTitle('推荐区块 1');
        $block1->setSubtitle('热门商品推荐');
        $block1->setValid(true);
        $block1->setCreatedBy('system');
        $block1->setUpdatedBy('system');

        $block2 = new RecommendBlock();
        $block2->setTitle('推荐区块 2');
        $block2->setSubtitle('新品上市');
        $block2->setValid(false);
        $block2->setCreatedBy('system');
        $block2->setUpdatedBy('system');

        $manager->persist($block1);
        $manager->persist($block2);
        $manager->flush();
    }
}
