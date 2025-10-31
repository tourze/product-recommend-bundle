<?php

declare(strict_types=1);

namespace ProductRecommendBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use ProductRecommendBundle\Entity\RelatedRecommend;

class RelatedRecommendFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $recommend1 = new RelatedRecommend();
        $recommend1->setVisitSpuId('VISIT001');
        $recommend1->setScene('product_detail');
        $recommend1->setRecommendSpuId('RECOMMEND001');
        $recommend1->setTextReason('相似商品');
        $recommend1->setTargetGroup('所有用户');
        $recommend1->setScore(4.8);
        $recommend1->setValid(true);
        $recommend1->setCreatedBy('system');
        $recommend1->setUpdatedBy('system');

        $recommend2 = new RelatedRecommend();
        $recommend2->setVisitSpuId('VISIT002');
        $recommend2->setScene('category_page');
        $recommend2->setRecommendSpuId('RECOMMEND002');
        $recommend2->setTextReason(null);
        $recommend2->setTargetGroup(null);
        $recommend2->setScore(null);
        $recommend2->setValid(false);
        $recommend2->setCreatedBy('system');
        $recommend2->setUpdatedBy('system');

        $manager->persist($recommend1);
        $manager->persist($recommend2);
        $manager->flush();
    }
}
