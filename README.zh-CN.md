# Product Recommend Bundle

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4-blue)](https://symfony.com/)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/your-repo/actions)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](https://codecov.io/gh/your-repo)

[English](README.md) | [中文](README.zh-CN.md)

一个功能全面的 Symfony Bundle，用于管理商品推荐，支持灵活的推荐位、
商品元素、标签和关联推荐功能。

## 特性

- **推荐位管理**：通过推荐位组织推荐内容，支持标题和副标题
- **商品元素**：管理单个商品推荐，包含 SPU ID、图片和推荐理由
- **元素标签**：通过灵活的标签系统对推荐进行分类
- **关联推荐**：创建商品间的关联推荐关系
- **EasyAdmin 集成**：内置管理控制器，便于管理
- **Doctrine 支持**：完整的 ORM 集成，支持时间戳、用户追踪和雪花 ID

## 安装

```bash
composer require tourze/product-recommend-bundle
```

## 快速开始

### 1. 启用 Bundle

在 `config/bundles.php` 中添加 Bundle：

```php
return [
    // ...
    ProductRecommendBundle\ProductRecommendBundle::class => ['all' => true],
];
```

### 2. 配置数据库

运行迁移以创建所需的数据表：

```bash
php bin/console doctrine:migrations:migrate
```

### 3. 基本用法

```php
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Entity\RecommendElement;

// 创建推荐位
$block = new RecommendBlock();
$block->setTitle('特色商品');
$block->setSubtitle('我们的热门推荐');
$block->setValid(true);

// 向推荐位添加商品
$element = new RecommendElement();
$element->setBlock($block);
$element->setSpuId('12345');
$element->setThumb('/images/product.jpg');
$element->setTextReason('本月最佳销量');
$element->setValid(true);

$entityManager->persist($block);
$entityManager->persist($element);
$entityManager->flush();
```

## 配置

Bundle 提供合理的默认配置，配置需求最小。

### 基本配置

在 `config/packages/product_recommend.yaml` 中添加：

```yaml
product_recommend:
    # 可选：配置默认评分
    default_score: 1.0
    # 可选：配置推荐理由最大长度
    max_text_reason_length: 500
```

### 数据库配置

确保在 `config/packages/doctrine.yaml` 中正确配置数据库连接。

## Dependencies

本 Bundle 需要以下依赖包：

### 核心依赖
- `symfony/framework-bundle ^6.4`
- `doctrine/orm ^3.0`
- `doctrine/doctrine-bundle ^2.13`

### Tourze 依赖
- `tourze/doctrine-snowflake-bundle` - 雪花 ID 生成
- `tourze/doctrine-timestamp-bundle` - 自动时间戳
- `tourze/doctrine-user-bundle` - 用户追踪
- `tourze/easy-admin-extra-bundle` - 增强的管理界面

### 可选依赖
- `knplabs/knp-menu ^3.7` - 菜单集成

## 管理界面

Bundle 包含用于管理的 EasyAdmin CRUD 控制器：

- `ProductRecommendBlockCrudController` - 管理推荐位
- `ProductRecommendElementCrudController` - 管理商品元素
- `ProductRecommendElementTagCrudController` - 管理元素标签
- `ProductRecommendRelatedCrudController` - 管理关联推荐

## Advanced Usage

### 自定义仓库使用

```php
use ProductRecommendBundle\Repository\RecommendBlockRepository;
use ProductRecommendBundle\Repository\RecommendElementRepository;

// 获取仓库
$blockRepo = $entityManager->getRepository(RecommendBlock::class);
$elementRepo = $entityManager->getRepository(RecommendElement::class);

// 查找活跃的推荐位
$activeBlocks = $blockRepo->findBy(['valid' => true]);

// 按推荐位查找元素
$elements = $elementRepo->findBy(['block' => $block, 'valid' => true]);
```

### 使用标签

```php
use ProductRecommendBundle\Entity\RecommendElementTag;

// 创建并分配标签
$tag = new RecommendElementTag();
$tag->setTitle('热门优惠');
$tag->setValid(true);

$element->addRecommendElementTag($tag);
```

### 关联推荐

```php
use ProductRecommendBundle\Entity\RelatedRecommend;

// 创建商品间关联推荐
$related = new RelatedRecommend();
$related->setVisitSpuId('12345');
$related->setScene('product_detail');
$related->setRecommendSpuId('67890');
$related->setTextReason('查看此商品的客户还喜欢');
$related->setScore(0.8);
$related->setValid(true);
```

### 性能优化

- 对大型集合使用 `fetch: 'EXTRA_LAZY'`
- 为频繁访问的推荐实现缓存
- 为大结果集考虑分页

### 验证

所有实体都包含全面的验证约束：

- 所有字符串字段的**长度验证**
- SPU ID 的**格式验证**（数字字符串）
- 图片缩略图的 **URL 验证**
- 评分的**范围验证**（0-100）

## License

此 Bundle 采用 MIT 许可证发布。
详情请参阅 [LICENSE](LICENSE) 文件。