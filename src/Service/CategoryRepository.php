<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Service;

use OxidEsales\Eshop\Application\Model\Category as CategoryModel;
use OxidEsales\Eshop\Application\Model\CategoryList as CategoryListModel;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\GraphQL\Example\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Example\DataType\Category;
use OxidEsales\GraphQL\Example\DataType\CategoryFilter;

use function array_filter;

class CategoryRepository
{
    /** @var QueryBuilderFactoryInterface $queryBuilderFactory */
    private $queryBuilderFactory;

    public function __construct(
        QueryBuilderFactoryInterface $queryBuilderFactory
    ) {
        $this->queryBuilderFactory = $queryBuilderFactory;
    }

    public function getById(string $id): Category
    {
        /** @var CategoryModel */
        $category = oxNew(CategoryModel::class);
        if (!$category->load($id)) {
            throw CategoryNotFound::byId($id);
        }
        return new Category($category);
    }

    /**
     * @return Category[]
     */
    public function getByFilter(?CategoryFilter $filter = null): array
    {
        $categories = [];
        $model = oxNew(CategoryModel::class);

        $queryBuilder = $this->queryBuilderFactory->create();
        $queryBuilder->select('*')
                     ->from($model->getViewName())
                     ->orderBy('oxid');

        $filters = array_filter($filter->getFilters());
        foreach ($filters as $field => $fieldFilter) {
            $fieldFilter->addToQuery($queryBuilder, $field);
        }

        $queryBuilder->getConnection()->setFetchMode(PDO::FETCH_ASSOC);
        /** @var \Doctrine\DBAL\Statement $result */
        $result = $queryBuilder->execute();
        foreach ($result as $row) {
            $category = clone $model;
            $category->assign($row);
            $categories[] = new Category($category);
        }

        return $categories;
    }

    public function save(Category $category): Category
    {
        $categoryModel = $category->getCategoryModel();
        if (!$categoryModel->save()) {
            throw new \Exception();
        }
        // reload model
        $categoryModel->load($categoryModel->getId());
        return new Category($categoryModel);
    }
}
