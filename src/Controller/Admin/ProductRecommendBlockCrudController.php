<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\BooleanFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\TextFilter;
use ProductRecommendBundle\Entity\RecommendBlock;

/**
 * @extends AbstractCrudController<RecommendBlock>
 */
#[AdminCrud(routePath: '/product-recommend/block', routeName: 'product_recommend_block')]
final class ProductRecommendBlockCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RecommendBlock::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')->hideOnForm(),
            TextField::new('title', '标题'),
            TextField::new('subtitle', '副标题'),
            BooleanField::new('valid', '有效'),
            CollectionField::new('elements', '推荐元素')->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('推荐位')
            ->setEntityLabelInPlural('推荐位管理')
            ->setPageTitle('index', '推荐位管理')
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(TextFilter::new('title', '标题'))
            ->add(BooleanFilter::new('valid', '有效'))
        ;
    }
}
