<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQL\Sample\Dao;

use OxidEsales\GraphQL\Sample\DataObject\User;

interface UserDaoInterface
{
    public function getUserById(string $userid): ?User;

    public function getUserByName(string $username, int $shopid): ?User;

    public function updateUser(User $user): void;

    public function saveUser(User $user): void;
}
