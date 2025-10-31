<?php

declare(strict_types=1);

namespace ProductRecommendBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ProductRecommendBundle\Entity\RecommendElementTag;

class RecommendElementTagFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $tag1 = new RecommendElementTag();
        $tag1->setTitle('热门标签');
        $tag1->setValid(true);

        $tag2 = new RecommendElementTag();
        $tag2->setTitle('新品标签');
        $tag2->setValid(false);

        $tag3 = new RecommendElementTag();
        $tag3->setTitle('无效标签');
        $tag3->setValid(null);

        $manager->persist($tag1);
        $manager->persist($tag2);
        $manager->persist($tag3);
        $manager->flush();
    }
}
