<?php

declare(strict_types=1);

namespace ProductRecommendBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Entity\RecommendElement;

class RecommendElementFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $block = new RecommendBlock();
        $block->setTitle('测试区块');
        $block->setValid(true);
        $block->setCreatedBy('system');
        $block->setUpdatedBy('system');

        $element1 = new RecommendElement();
        $element1->setSpuId('SPU001');
        $element1->setTextReason('热门推荐');
        $element1->setTargetGroup('所有用户');
        $element1->setScore(5.0);
        $element1->setBlock($block);
        $element1->setValid(true);
        $element1->setCreatedBy('system');
        $element1->setUpdatedBy('system');

        $element2 = new RecommendElement();
        $element2->setSpuId('SPU002');
        $element2->setTextReason('新品上市');
        $element2->setTargetGroup('新用户');
        $element2->setScore(4.5);
        $element2->setBlock($block);
        $element2->setValid(false);
        $element2->setCreatedBy('system');
        $element2->setUpdatedBy('system');

        $manager->persist($block);
        $manager->persist($element1);
        $manager->persist($element2);
        $manager->flush();
    }
}
