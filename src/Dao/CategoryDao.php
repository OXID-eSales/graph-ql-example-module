<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Example\DataObject\Category;
use OxidEsales\GraphQL\Example\DataObject\CategoryFilter;

use function array_filter;

class CategoryDao implements CategoryDaoInterface
{

    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function getCategoryById(string $id, int $languageId, int $shopId): ?Category
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(['OXID', 'OXTITLE', 'OXPARENTID', 'OXTIMESTAMP'])
                     ->from(\getViewName(
                         'oxcategories',
                         $languageId,
                         (string)$shopId
                     ))
                     ->where('OXID = :oxid')
                     ->setParameter('oxid', $id);
        $result = $queryBuilder->execute();
        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            return null;
        }
        $row = $result->fetch();
        if (!$row) {
            return null;
        }
        $category = new Category(
            $row['OXID'],
            $row['OXTITLE'],
            $row['OXPARENTID'],
            $row['OXTIMESTAMP']
        );
        return $category;
    }

    /**
     * @return Category[]
     */
    public function getCategories(CategoryFilter $filter, int $languageId, int $shopId): array
    {
        $categories = [];
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(['OXID', 'OXTITLE', 'OXPARENTID', 'OXTIMESTAMP'])
                     ->from(\getViewName(
                         'oxcategories',
                         $languageId,
                         (string)$shopId
                     ));

        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        $result = $queryBuilder->execute();

        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            return $categories;
        }

        foreach ($result as $row) {
            $categories[] = new Category(
                $row['OXID'],
                $row['OXTITLE'],
                $row['OXPARENTID'],
                $row['OXTIMESTAMP']
            );
        }
        return $categories;
    }

    public function createCategory(Category $category, int $languageId, int $shopId): Category
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $title = $languageId === 0 ? "OXTITLE" : "OXTITLE_$languageId";
        $values = [
            'OXID'       => ':oxid',
            'OXSHOPID'   => $shopId,
            $title       => ':title',
            'OXPARENTID' => ':parentid',
        ];
        $queryBuilder->setParameter('oxid', $category->getId())
                     ->setParameter('shopid', $shopId)
                     ->setParameter('title', $category->getTitle())
                     ->setParameter('parentid', $category->getParentid());

        $queryBuilder->insert('oxcategories')
                     ->values($values)
                     ->execute();

        return $category;
    }
}
