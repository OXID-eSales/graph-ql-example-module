<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\GraphQL\Example\Service;

use OxidEsales\Eshop\Application\Model\Category as CategoryModel;
use OxidEsales\Eshop\Application\Model\CategoryList as CategoryListModel;
use OxidEsales\GraphQL\Example\Exception\CategoryNotFound;
use OxidEsales\GraphQL\Example\DataObject\Category;
use OxidEsales\GraphQL\Example\DataObject\CategoryFilter;

class CategoryRepository
{

    public function getById(string $id): Category
    {
        /** @var CategoryModel */
        $category = oxNew(CategoryModel::class);
        if (!$category->load($id)) {
            throw CategoryNotFound::byId($id);
        }
        return Category::createFromModel($category);
    }

    /**
     * @return Category[]
     */
    public function getByFilter(?CategoryFilter $filter = null): array
    {
        /** @var CategoryListModel */
        $categoryList = oxNew(CategoryListModel::class);
        $categoryList->loadList();
        $categories = [];
        /** @var CategoryModel $category */
        foreach ($categoryList as $category) {
            $categories[] = Category::createFromModel($category);
        }
        // as the CategoryList model does not allow us to easily inject conditions
        // into the SQL where clause, we filter after the fact. This stinks, but
        // at the moment this is the easiest solution
        if ($filter !== null) {
            $parentIdFilter = $filter->getFilters()['oxparentid'];
            $categories = array_filter(
                $categories,
                function (Category $category) use ($parentIdFilter) {
                    return $parentIdFilter->equals() == $category->getParentId();
                }
            );
        }
        return $categories;
    }

    public function save(Category $category): Category
    {
        $category = $category->createModel();
        if (!$category->save()) {
            throw new \Exception();
        }
        return Category::createFromModel($category);
    }
}
