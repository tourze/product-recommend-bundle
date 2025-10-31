<?php

declare(strict_types=1);

namespace ProductRecommendBundle\Tests;

use Knp\Menu\ItemInterface;
use Knp\Menu\MenuFactory;
use Knp\Menu\MenuItem;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use ProductRecommendBundle\AdminMenu;
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Entity\RecommendElement;
use ProductRecommendBundle\Entity\RecommendElementTag;
use ProductRecommendBundle\Entity\RelatedRecommend;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private AdminMenu $adminMenu;

    private LinkGeneratorInterface $linkGenerator;

    protected function onSetUp(): void
    {
        // 创建测试专用的LinkGenerator实现，避免Mock导致的类型推断失败
        $this->linkGenerator = new class implements LinkGeneratorInterface {
            public function getCurdListPage(string $entityClass): string
            {
                return match ($entityClass) {
                    RecommendBlock::class => '/admin/recommend-block',
                    RecommendElement::class => '/admin/recommend-element',
                    RecommendElementTag::class => '/admin/recommend-element-tag',
                    RelatedRecommend::class => '/admin/related-recommend',
                    default => '/admin/unknown',
                };
            }

            public function extractEntityFqcn(string $url): ?string
            {
                return match (true) {
                    str_contains($url, '/admin/recommend-block') => RecommendBlock::class,
                    str_contains($url, '/admin/recommend-element') => RecommendElement::class,
                    str_contains($url, '/admin/recommend-element-tag') => RecommendElementTag::class,
                    str_contains($url, '/admin/related-recommend') => RelatedRecommend::class,
                    default => null,
                };
            }

            public function setDashboard(string $dashboardControllerFqcn): void
            {
                // 测试环境下不需要实际设置 Dashboard，保留空实现
            }
        };

        self::getContainer()->set(LinkGeneratorInterface::class, $this->linkGenerator);
        $this->adminMenu = self::getService(AdminMenu::class);
    }

    protected function getMenuProvider(): object
    {
        return $this->adminMenu;
    }

    public function testAddsProductRecommendMenuToExistingMenu(): void
    {
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);

        // 创建真实的菜单项（使用MenuFactory和MenuItem，而不是Mock）
        $factory = new MenuFactory();
        $rootItem = new MenuItem('root', $factory);
        $productRecommendItem = $rootItem->addChild('商品推荐');

        // 执行菜单构建
        $this->adminMenu->__invoke($rootItem);

        // 验证商品推荐菜单存在
        $productMenu = $rootItem->getChild('商品推荐');
        $this->assertInstanceOf(ItemInterface::class, $productMenu);
        $this->assertSame($productRecommendItem, $productMenu);

        // 验证所有子菜单都被正确添加
        $recommendBlockMenuItem = $productMenu->getChild('推荐位管理');
        $this->assertInstanceOf(ItemInterface::class, $recommendBlockMenuItem);
        $this->assertSame('/admin/recommend-block', $recommendBlockMenuItem->getUri());

        $recommendElementMenuItem = $productMenu->getChild('推荐元素管理');
        $this->assertInstanceOf(ItemInterface::class, $recommendElementMenuItem);
        $this->assertSame('/admin/recommend-element', $recommendElementMenuItem->getUri());

        $recommendElementTagMenuItem = $productMenu->getChild('推荐元素标签管理');
        $this->assertInstanceOf(ItemInterface::class, $recommendElementTagMenuItem);
        $this->assertSame('/admin/recommend-element-tag', $recommendElementTagMenuItem->getUri());

        $relatedRecommendMenuItem = $productMenu->getChild('相关推荐管理');
        $this->assertInstanceOf(ItemInterface::class, $relatedRecommendMenuItem);
        $this->assertSame('/admin/related-recommend', $relatedRecommendMenuItem->getUri());
    }

    public function testCreatesNewProductRecommendMenuIfNotExists(): void
    {
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);

        // 创建不含商品推荐菜单的主菜单
        $factory = new MenuFactory();
        $rootItem = new MenuItem('root', $factory);

        // 执行菜单构建
        $this->adminMenu->__invoke($rootItem);

        // 验证商品推荐菜单被创建
        $productMenu = $rootItem->getChild('商品推荐');
        $this->assertInstanceOf(ItemInterface::class, $productMenu);

        // 验证所有子菜单都被正确添加
        $recommendBlockMenuItem = $productMenu->getChild('推荐位管理');
        $this->assertInstanceOf(ItemInterface::class, $recommendBlockMenuItem);
        $this->assertSame('/admin/recommend-block', $recommendBlockMenuItem->getUri());

        $recommendElementMenuItem = $productMenu->getChild('推荐元素管理');
        $this->assertInstanceOf(ItemInterface::class, $recommendElementMenuItem);
        $this->assertSame('/admin/recommend-element', $recommendElementMenuItem->getUri());

        $recommendElementTagMenuItem = $productMenu->getChild('推荐元素标签管理');
        $this->assertInstanceOf(ItemInterface::class, $recommendElementTagMenuItem);
        $this->assertSame('/admin/recommend-element-tag', $recommendElementTagMenuItem->getUri());

        $relatedRecommendMenuItem = $productMenu->getChild('相关推荐管理');
        $this->assertInstanceOf(ItemInterface::class, $relatedRecommendMenuItem);
        $this->assertSame('/admin/related-recommend', $relatedRecommendMenuItem->getUri());
    }

    public function testHandlesNullProductRecommendMenuGracefully(): void
    {
        $this->assertInstanceOf(AdminMenu::class, $this->adminMenu);

        // 创建不含任何商品相关菜单的主菜单
        $factory = new MenuFactory();
        $rootItem = new MenuItem('root', $factory);

        // 执行菜单构建（应当优雅处理空菜单）
        $this->adminMenu->__invoke($rootItem);

        // 验证商品推荐菜单被创建
        $productMenu = $rootItem->getChild('商品推荐');
        $this->assertInstanceOf(ItemInterface::class, $productMenu);

        // 验证子菜单都被正确添加
        $this->assertInstanceOf(ItemInterface::class, $productMenu->getChild('推荐位管理'));
        $this->assertInstanceOf(ItemInterface::class, $productMenu->getChild('推荐元素管理'));
        $this->assertInstanceOf(ItemInterface::class, $productMenu->getChild('推荐元素标签管理'));
        $this->assertInstanceOf(ItemInterface::class, $productMenu->getChild('相关推荐管理'));
    }
}
