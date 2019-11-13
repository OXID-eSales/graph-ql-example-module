<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Example\DataObject\Category;

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
        $title = $languageId === 0 ? "OXTITLE" : "OXTITLE_$languageId";

        $queryBuilder->select(['OXID', $title, 'OXPARENTID'])
                     ->from('oxcategories')
                     ->where($queryBuilder->expr()->andX(
                         $queryBuilder->expr()->eq('OXID', ':oxid'),
                         $queryBuilder->expr()->eq('OXSHOPID', ':shopid')
                     ))
                     ->setParameter('oxid', $id)
                     ->setParameter('shopid', $shopId);
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
            $row[$title],
            $row['OXPARENTID']
        );
        return $category;
    }

    /**
     * @return Category[]
     */
    public function getCategoriesByParentId(string $parentid, int $languageId, int $shopId): array
    {
        $categories = [];
        $queryBuilder = $this->queryBuilderFactory->create();
        $title = $languageId === 0 ? "OXTITLE" : "OXTITLE_$languageId";
        $queryBuilder->select(['OXID', $title, 'OXPARENTID'])
                     ->from('oxcategories')
                     ->where($queryBuilder->expr()->andX(
                         $queryBuilder->expr()->eq('OXPARENTID', ':oxparentid'),
                         $queryBuilder->expr()->eq('OXSHOPID', ':shopid')
                     ))
                     ->setParameter('oxparentid', $parentid)
                     ->setParameter('shopid', $shopId);
        $result = $queryBuilder->execute();

        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            return $categories;
        }

        foreach ($result as $row) {
            $categories[] = new Category(
                $row['OXID'],
                $row[$title],
                $row['OXPARENTID']
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
                     ->setParameter('title', $category->getName())
                     ->setParameter('parentid', $category->getParentid());

        $queryBuilder->insert('oxcategories')
                     ->values($values)
                     ->execute();

        return $category;
    }
}
