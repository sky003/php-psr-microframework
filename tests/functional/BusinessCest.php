<?php
declare(strict_types = 1);

namespace Tests\Functional;

use App\Entity\Business;
use Tests\FunctionalTester;

class BusinessCest
{
    private const VALID_JWT = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.'
                              .'eyJqdGkiOiJqd3RfNWI3ZmZkNDc2OGVkMDIuMzAwNjExOTQifQ.'
                              .'yWx6kHmWIM8Blq82LoYt3Pegmm81YH_p4FfEIyawd2c';

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
        $I->amBearerAuthenticated(self::VALID_JWT);
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
        $I->amBearerAuthenticated(self::VALID_JWT);
        $I->sendPOST('/api/v1/businesses', $data);

        $I->expect('the request returned an error');
        $I->seeResponseCodeIs(422);
    }

    public function testUpdate(FunctionalTester $I): void
    {
        $entity = new Business();
        $I->persistEntity($entity, [
            'name'             => 'Acme',
            'constructionYear' => new \DateTime('2013-01-01'),
            'class'            => 1,
            'governmental'     => false,
        ]);

        $data = [
            'name'             => 'New Acme',
            'constructionYear' => 2017,
            'governmental'     => false,
        ];

        $I->wantTo('make sure the business updating works as expected');

        $I->amGoingTo('make a request to the business update action');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated(self::VALID_JWT);
        $I->sendPATCH('/api/v1/businesses/'.$entity->getId(), $data);

        $I->expect('the request successfully handled');
        $I->seeResponseCodeIs(200);
        $I->seeResponseMatchesJsonType([
            'updatedAt' => 'string',
        ]);
        $I->seeResponseContainsJson([
            'id'               => $entity->getId(),
            'name'             => $data['name'],
            'constructionYear' => $data['constructionYear'],
            'class'            => $entity->getClass(),
            'governmental'     => $data['governmental'],
        ]);
    }

    public function testUpdateWithNotValidId(FunctionalTester $I): void
    {
        $entity = new Business();
        $I->persistEntity($entity, [
            'name'             => 'Acme',
            'constructionYear' => new \DateTime('2013-01-01'),
            'class'            => 1,
            'governmental'     => false,
        ]);

        $data = [
            'name'             => 'New Acme',
            'constructionYear' => 2017,
            'governmental'     => false,
        ];

        $I->wantTo('make sure the updating of not existent business will return correct error');

        $I->amGoingTo('make a request to update the business which is definitely not exists');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->amBearerAuthenticated(self::VALID_JWT);
        $I->sendPATCH('/api/v1/businesses/'.($entity->getId() + 1000), $data);

        $I->expect('the request returned an error');
        $I->seeResponseCodeIs(404);
    }

    public function testGetList(FunctionalTester $I): void
    {
        $I->persistEntity(new Business(), [
            'name'             => 'Searchable Acme',
            'constructionYear' => new \DateTime('2008-01-01'),
            'class'            => 3,
            'governmental'     => true,
        ]);
        $I->persistEntity(new Business(), [
            'name'             => 'Searchable Acme 2',
            'constructionYear' => new \DateTime('2009-01-01'),
            'class'            => 4,
            'governmental'     => false,
        ]);

        $I->wantTo('make sure the endpoint to get business list works as expected');

        $I->amGoingTo('make a request to the business update action');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/api/v1/businesses?limit=2&q=Searchable');

        $I->expect('the request successfully handled');
        $I->seeResponseCodeIs(200);
        $I->seeResponseContainsJson([
            [
                'name' => 'Searchable Acme',
            ],
            [
                'name' => 'Searchable Acme 2',
            ],
        ]);
    }
}
