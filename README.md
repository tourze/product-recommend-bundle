# Product Recommend Bundle

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![Symfony](https://img.shields.io/badge/symfony-%5E6.4-blue)](https://symfony.com/)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen)](https://github.com/your-repo/actions)
[![Code Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](https://codecov.io/gh/your-repo)

[English](README.md) | [中文](README.zh-CN.md)

A comprehensive Symfony bundle for managing product recommendations with 
flexible recommend blocks, product elements, tags, and related recommendations.

## Features

- **Recommend Blocks**: Organize recommendations by blocks with title and subtitle
- **Product Elements**: Manage individual product recommendations with SPU IDs, images, and reasons
- **Element Tags**: Categorize recommendations with flexible tagging system
- **Related Recommendations**: Create product-to-product relationship recommendations
- **EasyAdmin Integration**: Built-in admin controllers for easy management
- **Doctrine Support**: Full ORM integration with timestamp, user tracking, and snowflake ID support

## Installation

```bash
composer require tourze/product-recommend-bundle
```

## Quick Start

### 1. Enable the Bundle

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    ProductRecommendBundle\ProductRecommendBundle::class => ['all' => true],
];
```

### 2. Configure Database

Run migrations to create the required tables:

```bash
php bin/console doctrine:migrations:migrate
```

### 3. Basic Usage

```php
use ProductRecommendBundle\Entity\RecommendBlock;
use ProductRecommendBundle\Entity\RecommendElement;

// Create a recommendation block
$block = new RecommendBlock();
$block->setTitle('Featured Products');
$block->setSubtitle('Our top recommendations');
$block->setValid(true);

// Add products to the block
$element = new RecommendElement();
$element->setBlock($block);
$element->setSpuId('12345');
$element->setThumb('/images/product.jpg');
$element->setTextReason('Best seller this month');
$element->setValid(true);

$entityManager->persist($block);
$entityManager->persist($element);
$entityManager->flush();
```

## Administration

The bundle includes EasyAdmin CRUD controllers for managing:

- `ProductRecommendBlockCrudController` - Manage recommendation blocks
- `ProductRecommendElementCrudController` - Manage product elements
- `ProductRecommendElementTagCrudController` - Manage element tags
- `ProductRecommendRelatedCrudController` - Manage related recommendations

## Configuration

The bundle provides sensible defaults and minimal configuration requirements.

### Basic Configuration

Add to your `config/packages/product_recommend.yaml`:

```yaml
product_recommend:
    # Optional: Configure default scoring
    default_score: 1.0
    # Optional: Configure max text reason length
    max_text_reason_length: 500
```

### Database Configuration

Ensure your database connection is properly configured in `config/packages/doctrine.yaml`.

## Dependencies

This bundle requires the following packages:

### Core Dependencies
- `symfony/framework-bundle ^6.4`
- `doctrine/orm ^3.0`
- `doctrine/doctrine-bundle ^2.13`

### Tourze Dependencies
- `tourze/doctrine-snowflake-bundle` - For snowflake ID generation
- `tourze/doctrine-timestamp-bundle` - For automatic timestamps
- `tourze/doctrine-user-bundle` - For user tracking
- `tourze/easy-admin-extra-bundle` - For enhanced admin interface

### Optional Dependencies
- `knplabs/knp-menu ^3.7` - For menu integration

## Advanced Usage

### Custom Repository Usage

```php
use ProductRecommendBundle\Repository\RecommendBlockRepository;
use ProductRecommendBundle\Repository\RecommendElementRepository;

// Get repository
$blockRepo = $entityManager->getRepository(RecommendBlock::class);
$elementRepo = $entityManager->getRepository(RecommendElement::class);

// Find active blocks
$activeBlocks = $blockRepo->findBy(['valid' => true]);

// Find elements by block
$elements = $elementRepo->findBy(['block' => $block, 'valid' => true]);
```

### Working with Tags

```php
use ProductRecommendBundle\Entity\RecommendElementTag;

// Create and assign tags
$tag = new RecommendElementTag();
$tag->setTitle('Hot Deal');
$tag->setValid(true);

$element->addRecommendElementTag($tag);
```

### Related Recommendations

```php
use ProductRecommendBundle\Entity\RelatedRecommend;

// Create product-to-product recommendations
$related = new RelatedRecommend();
$related->setVisitSpuId('12345');
$related->setScene('product_detail');
$related->setRecommendSpuId('67890');
$related->setTextReason('Customers who viewed this also liked');
$related->setScore(0.8);
$related->setValid(true);
```

### Performance Optimization

- Use `fetch: 'EXTRA_LAZY'` for large collections
- Implement caching for frequently accessed recommendations
- Consider pagination for large result sets

### Validation

All entities include comprehensive validation constraints:

- **Length validation** for all string fields
- **Format validation** for SPU IDs (numeric strings)
- **URL validation** for image thumbnails
- **Range validation** for scores (0-100)

## License

This bundle is released under the MIT license. 
See the [LICENSE](LICENSE) file for more information.
