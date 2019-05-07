<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Tests\Acceptance;

use OxidEsales\GraphQl\Framework\RequestReader;
use OxidEsales\GraphQl\Framework\RequestReaderInterface;
use OxidEsales\GraphQl\Sample\Dao\CategoryDaoInterface;
use OxidEsales\GraphQl\Sample\Dao\OxObjectCategoryDao;
use OxidEsales\GraphQl\Tests\Acceptance\BaseGraphQlAcceptanceTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class OxObjectCategoryTest extends CategoryTest
{

    protected function beforeContainerCompile()
    {
        $definition = $this->container->getDefinition(CategoryDaoInterface::class);
        $definition->setClass(OxObjectCategoryDao::class);
    }

    public function testGetCategoryEn()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "$this->subId1S1") {
        name
    }
}
EOQ;
        $token = $this->createToken('anonymous');
        $token->setLang('en');
        $token->setShopid(1);

        $this->executeQueryWithToken($query, $token);

        $this->assertHttpStatusOK();
        $this->assertEquals('subcategory 1-1', $this->queryResult['data']['category']['name']);

    }

    public function testGetCategoryDe()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "$this->subId1S1") {
        name
    }
}
EOQ;
        $token = $this->createToken('anonymous');
        $token->setLang('de');
        $token->setShopid(1);

        $this->executeQueryWithToken($query, $token);

        $this->assertHttpStatusOK();
        $this->assertEquals('Unterkategorie 1-1', $this->queryResult['data']['category']['name']);

    }

    public function testGetCategoryShop2()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "$this->subId1S1") {
        name
    }
}
EOQ;
        $token = $this->createToken('anonymous');
        $token->setLang('de');
        $token->setShopid(2);

        $this->executeQueryWithToken($query, $token);

        $this->assertHttpStatus(404);

    }

}
