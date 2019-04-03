<?php declare(strict_types=1);

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\GraphQl\Sample\Tests\Acceptance;

use OxidEsales\GraphQl\Tests\Acceptance\BaseAcceptanceTestCase;

class CategoryTest extends BaseAcceptanceTestCase
{

    public function testCategory()
    {

        $query = <<<EOQ
query TestQuery { 
    category (categoryid: "fad7facadcb7d4297f033d242aa0d310") {
        name
    }
}
EOQ;
        $this->executeQuery($query);

        $this->assertEquals(200, $this->httpStatus);
        $this->assertEquals('Für Ihn', $this->queryResult['data']['category']['name']);
    }
}
