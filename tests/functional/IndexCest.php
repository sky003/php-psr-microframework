<?php
namespace Tests\Functional;

use Tests\FunctionalTester;

/**
 * Test to make sure an application bootstrap actually working as expected.
 *
 * @author Anton Pelykh <anton.pelykh.dev@gmail.com>
 */
class IndexCest
{
    public function testIndex(FunctionalTester $I): void
    {
        $I->wantTo('make sure the application bootstrap works as expected');

        $I->amGoingTo('make a request to index action');
        $I->sendGET('/api/v1');

        $I->expect('the request successfully handled');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([]);
    }

    public function testHttpErrorHandling(FunctionalTester $I): void
    {
        $I->wantTo('make sure the application bootstrap handles the http errors');

        $I->amGoingTo('make a request to not existent endpoint');
        $I->sendGET('/api/v1/some-not-existent-endpoint');

        $I->expect('the http error');
        $I->seeResponseCodeIs(404);
        $I->seeResponseContainsJson([
            'message' => 'Not Found',
        ]);
    }
}
