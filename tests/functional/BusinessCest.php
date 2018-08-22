<?php
declare(strict_types = 1);

namespace Tests\Functional;

use Tests\FunctionalTester;

class BusinessCest
{
    public function testCreate(FunctionalTester $I): void
    {
        $data = [
            'name'             => 'Acme',
            'constructionYear' => 2015,
            'class'            => 1,
            'governmental'     => false,
        ];

        $I->wantTo('make sure the business creation works as expected');

        $I->amGoingTo('make a request to the business creation action');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/v1/businesses', $data);

        $I->expect('the request successfully handled');
        $I->seeResponseCodeIs(201);
        $I->seeResponseMatchesJsonType([
            'id'        => 'integer',
            'createdAt' => 'string',
        ]);
        $I->seeResponseContainsJson([
            'name'             => $data['name'],
            'constructionYear' => $data['constructionYear'],
            'class'            => $data['class'],
            'governmental'     => $data['governmental'],
        ]);
    }

    public function testCreateWithNotValidData(FunctionalTester $I): void
    {
        $data = [
            'constructionYear' => 2020,
            'governmental'     => false,
        ];

        $I->wantTo('make sure the business will not be created if data is not valid');

        $I->amGoingTo('make a request to the business creation action');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/v1/businesses', $data);

        $I->expect('the request returned an error');
        $I->seeResponseCodeIs(422);
    }
}
