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

    public function getCategoryById(string $id, int $shopId): Category
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(['OXID', 'OXTITLE', 'OXPARENTID'])
                     ->from('oxcategories')
                     ->where($queryBuilder->expr()->andX(
                         $queryBuilder->expr()->eq('OXID', ':oxid'),
                         $queryBuilder->expr()->eq('OXSHOPID', ':shopid')
                     ))
                     ->setParameter('oxid', $id)
                     ->setParameter('shopid', $shopId);
        $result = $queryBuilder->execute();
        $row = $result->fetch();
        if (!$row) {
            throw new \Exception("Category with id \"$id\" not found.");
        }
        $category = new Category(
            $row['OXID'],
            $row['OXTITLE'],
            $row['OXPARENTID']
        );
        return $category;
    }

    /**
     * @return Category[]
     */
    public function getCategoriesByParentId(string $parentid, int $shopId): array
    {
        $categories = [];
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(['OXID', 'OXTITLE', 'OXPARENTID'])
                     ->from('oxcategories')
                     ->where($queryBuilder->expr()->andX(
                         $queryBuilder->expr()->eq('OXPARENTID', ':oxparentid'),
                         $queryBuilder->expr()->eq('OXSHOPID', ':shopid')
                     ))
                     ->setParameter('oxparentid', $parentid)
                     ->setParameter('shopid', $shopId);
        $result = $queryBuilder->execute();

        foreach ($result as $row) {
            $categories[] = new Category(
                $row['OXID'],
                $row['OXTITLE'],
                $row['OXPARENTID']
            );
        }
        return $categories;
    }

    /*

    public function getCategory(string $categoryId, string $lang, int $shopId): Category
    {
        $viewName = 'oxv_oxcategories_' . $shopId . '_' . $lang;
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(['OXTITLE', 'OXID', 'OXPARENTID'])
            ->from($viewName)
            ->where($queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('OXID', ':oxid'),
                        $queryBuilder->expr()->eq('OXSHOPID', ':shopid')
                )
            )
            ->setParameter('oxid', $categoryId)
            ->setParameter('shopid', $shopId);
        $result = $queryBuilder->execute();
        $row = $result->fetch();
        if (! $row) {
            throw new ObjectNotFoundException("Category with id \"$categoryId\" not found.");
        }
        else {
            return $this->rowToCategory($row);
        }
    }

    public function getCategories(string $lang, int $shopId, $parentid=null)
    {
        $viewName = 'oxv_oxcategories_' . $shopId . '_' . $lang;
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(['OXTITLE', 'OXID', 'OXPARENTID'])
            ->from($viewName)
            ->where($queryBuilder->expr()->andX(
                        $queryBuilder->expr()->eq('OXPARENTID', ':oxparentid'),
                        $queryBuilder->expr()->eq('OXSHOPID', ':shopid')
                    )
                )
            ->setParameter('shopid', $shopId);
        if ($parentid === null) {
            $queryBuilder->setParameter('oxparentid', 'oxrootid');
        }
        else {
            $queryBuilder->setParameter('oxparentid', $parentid);
        }
        $result = $queryBuilder->execute();
        $categoryList = [];
        foreach($result as $row) {
            $categoryList[] = $this->rowToCategory($row);
        }
        return $categoryList;

    }

    public function addCategory(array $names, int $shopId, string $parentId = null): string
    {

        $queryBuilder = $this->queryBuilderFactory->create();
        $values = ['OXSHOPID' => $shopId, 'OXTITLE' => ':title'];
        $queryBuilder->setParameter('title', $names[0]);
        for ($i = 1; $i < sizeof($names); $i++) {
            $values["OXTITLE_$i"] = ":title_$i";
            $queryBuilder->setParameter("title_$i", $names[$i]);
        }
        $values['OXPARENTID'] = ':parentid';
        if ($parentId === null) {
            $queryBuilder->setParameter('parentid', 'oxrootid');
        }
        else {
            $queryBuilder->setParameter('parentid', $parentId);
        }
        $values['OXID'] = ':oxid';
        $id = $this->legacyWrapper->createUid();
        $queryBuilder->setParameter('oxid', $id)
            ->setParameter('shopid', $shopId);

        $queryBuilder->insert('oxcategories')->values($values)->execute();

        $queryBuilderJoin = $this->queryBuilderFactory->create();
        $queryBuilderJoin->insert('oxcategories2shop')
            ->values(['OXSHOPID' => ':shopid', 'OXMAPOBJECTID' => ':mapid'])
            ->setParameter('shopid', $shopId)
            ->setParameter('mapid', $this->getMapId($id))
            ->execute();

        return $id;
    }

    private function getMapId($categoryId)
    {
        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select(['OXMAPID', 'OXID', 'OXPARENTID'])
            ->from('oxcategories')
            ->where($queryBuilder->expr()->eq('OXID', ':oxid'))
            ->setParameter('oxid', $categoryId);
        $result = $queryBuilder->execute();
        $row = $result->fetch();
        if (! $row) {
            throw new ObjectNotFoundException("Category with id \"$categoryId\" not found.");
        }
        else {
            return $row['OXMAPID'];
        }

    }

    private function rowToCategory($row)
    {
        $category = new Category();
        $category->setName($row['OXTITLE']);
        $category->setId($row['OXID']);
        if ($row['OXPARENTID'] != 'oxrootid') {
            $category->setParentid($row['OXPARENTID']);
        } else
        {
            $category->setParentid(null);
        }
        return $category;
    }
     */
}
