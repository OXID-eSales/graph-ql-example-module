<?php
/**
 * Created by PhpStorm.
 * User: michael
 * Date: 18.03.19
 * Time: 12:45
 */

namespace OxidEsales\GraphQl\Sample\Dao;

interface CategoryDaoInterface
{
    public function getCategory(string $categoryId, string $lang, int $shopId);

    public function getCategories(string $lang, int $shopId, $parentid = null);

    public function addCategory(array $names, int $shopId, string $parentId = null);

}