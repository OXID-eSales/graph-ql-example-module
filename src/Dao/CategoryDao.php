<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Dao;

use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Base\Service\LegacyServiceInterface;
use OxidEsales\GraphQL\Example\DataObject\Category;
use OxidEsales\GraphQL\Example\Exception\CategoryNotFoundException;

class CategoryDao implements CategoryDaoInterface
{

    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    /** @var LegacyServiceInterface */
    private $legacyService;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory,
        LegacyServiceInterface $legacyService
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
        $this->legacyService = $legacyService;
    }

    /**
     * @throws CategoryNotFoundException
     */
    public function getCategoryById(string $id, int $languageId): ?Category
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $title = $languageId === 0 ? "OXTITLE" : "OXTITLE_$languageId";

        $queryBuilder->select(['OXID', $title, 'OXPARENTID'])
                     ->from('oxcategories')
                     ->where($queryBuilder->expr()->andX(
                         $queryBuilder->expr()->eq('OXID', ':oxid')
                     ))
                     ->setParameter('oxid', $id);
        $result = $queryBuilder->execute();
        if (!$result instanceof \Doctrine\DBAL\Driver\Statement) {
            throw new CategoryNotFoundException("Error executing category query.");
        }
        $row = $result->fetch();
        if (!$row) {
            throw new CategoryNotFoundException("There is no category with id '$id'.");
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
     * @throws CategoryNotFoundException
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
            throw new CategoryNotFoundException('Error accessing database.');
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

    public function saveCategory(Category $category, int $languageId, int $shopId): Category
    {
        if ($category->getId() === null) {
            return $this->insertCategory($category, $languageId, $shopId);
        } else {
            return $this->updateCategory($category, $languageId, $shopId);
        }
    }

    private function insertCategory(Category $category, int $languageId, int $shopId): Category
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $title = $languageId === 0 ? "OXTITLE" : "OXTITLE_$languageId";
        $values = [
            'OXID'       => ':oxid',
            'OXSHOPID'   => $shopId,
            $title       => ':title',
            'OXPARENTID' => ':parentid',
        ];
        $categoryId = $this->legacyService->createUniqueIdentifier();
        $queryBuilder->setParameter('oxid', $categoryId)
                     ->setParameter('shopid', $shopId)
                     ->setParameter('title', $category->getName())
                     ->setParameter('parentid', $category->getParentId());

        $queryBuilder->insert('oxcategories')
                     ->values($values)
                     ->execute();

        return $this->getCategoryById($categoryId, $languageId);
    }

    private function updateCategory(Category $category, int $languageId, int $shopId): Category
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $title = $languageId === 0 ? "OXTITLE" : "OXTITLE_$languageId";

        $queryBuilder->update('oxcategories')
            ->set($title, ':title')
            ->set('OXPARENTID', ':parentid')
            ->where($queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('OXSHOPID', ':shopid'),
                $queryBuilder->expr()->eq('OXID', ':oxid')
            ))->setParameter('oxid', $category->getId())
            ->setParameter('shopid', $shopId)
            ->setParameter('parentid', $category->getParentid())
            ->setParameter('title', $category->getName())
            ->execute();

        return $this->getCategoryById($category->getId(), $languageId);
    }

    public function alterName(string $categoryId, string $name, int $languageId, int $shopId): Category
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $title = $languageId === 0 ? "OXTITLE" : "OXTITLE_$languageId";
        $values = [
            $title       => ':title'
        ];
        $queryBuilder->setParameter('oxid', $categoryId)
            ->setParameter('shopid', $shopId)
            ->setParameter('title', $name);

        $queryBuilder->update('oxcategories')
            ->values($values)
            ->where($queryBuilder->expr()->andX(
                $queryBuilder->expr()->eq('OXSHOPID', 'shopid'),
                $queryBuilder->expr()->eq('OXID', 'oxid')
            ))
            ->execute();

        return $this->getCategoryById($categoryId, $languageId, $shopId);
    }
}
