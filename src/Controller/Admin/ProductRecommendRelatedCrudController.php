<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use ProductRecommendBundle\Entity\RelatedRecommend;

/**
 * @extends AbstractCrudController<RelatedRecommend>
 */
#[AdminCrud(routePath: '/product-recommend/related', routeName: 'product_recommend_related')]
final class ProductRecommendRelatedCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RelatedRecommend::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('visitSpuId', '访问SPUID'),
            TextField::new('scene', '场景'),
            TextField::new('recommendSpuId', '推荐SPUID'),
            TextareaField::new('textReason', '推荐原因'),
            TextField::new('targetGroup', '目标分组'),
            NumberField::new('score', '分数'),
            IntegerField::new('sortNumber', '次序值'),
            BooleanField::new('valid', '有效'),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('相关推荐')
            ->setEntityLabelInPlural('相关推荐管理')
            ->setPageTitle('index', '相关推荐管理')
            ->setDefaultSort(['sortNumber' => 'ASC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('visitSpuId', '访问SPUID'))
            ->add(TextFilter::new('scene', '场景'))
            ->add(TextFilter::new('recommendSpuId', '推荐SPUID'))
            ->add(BooleanFilter::new('valid', '有效'))
            ->add(NumericFilter::new('score', '分数'))
        ;
    }
}
