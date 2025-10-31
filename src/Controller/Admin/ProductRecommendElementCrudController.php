<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\NumericFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use ProductRecommendBundle\Entity\RecommendElement;

/**
 * @extends AbstractCrudController<RecommendElement>
 */
#[AdminCrud(routePath: '/product-recommend/element', routeName: 'product_recommend_element')]
final class ProductRecommendElementCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecommendElement::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            AssociationField::new('block', '推荐位'),
            TextField::new('spuId', '商品ID'),
            UrlField::new('thumb', '图片'),
            TextareaField::new('textReason', '推荐理由'),
            TextField::new('targetGroup', '目标分组'),
            NumberField::new('score', '分数'),
            IntegerField::new('sortNumber', '排序'),
            BooleanField::new('valid', '有效'),
            AssociationField::new('recommendElementTags', '标签')->hideOnIndex(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('推荐元素')
            ->setEntityLabelInPlural('推荐元素管理')
            ->setPageTitle('index', '推荐元素管理')
            ->setDefaultSort(['sortNumber' => 'ASC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('block', '推荐位'))
            ->add(TextFilter::new('spuId', '商品ID'))
            ->add(BooleanFilter::new('valid', '有效'))
            ->add(NumericFilter::new('score', '分数'))
        ;
    }
}
