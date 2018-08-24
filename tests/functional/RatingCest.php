<?php
declare(strict_types = 1);

namespace Tests\Functional;

use App\Entity\Business;
use Tests\FunctionalTester;

class RatingCest
{
    public function testCreate(FunctionalTester $I): void
    {
        $entity = new Business();
        $I->persistEntity($entity, [
            'name'             => 'Acme',
            'constructionYear' => new \DateTime('2011-01-01'),
            'class'            => 1,
            'governmental'     => false,
        ]);

        $data = [
            'value' => 4,
        ];

        $I->wantTo('make sure the business rating works as expected');

        $I->amGoingTo('make a request to the business rating action');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/v1/businesses/'.$entity->getId().'/rating', $data);

        $I->expect('the request successfully handled');
        $I->seeResponseCodeIs(201);
        $I->seeResponseMatchesJsonType([
            'id'        => 'integer',
            'createdAt' => 'string',
        ]);
        $I->seeResponseContainsJson([
            'value' => $data['value'],
        ]);
    }

    public function testCreateWithNotValidaData(FunctionalTester $I): void
    {
        $entity = new Business();
        $I->persistEntity($entity, [
            'name'             => 'Acme',
            'constructionYear' => new \DateTime('2011-01-01'),
            'class'            => 1,
            'governmental'     => false,
        ]);

        $data = [
            'value' => 7,
        ];

        $I->wantTo('make sure the business will not be rated if the data is not valid');

        $I->amGoingTo('make a request to the business rating action');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/v1/businesses/'.$entity->getId().'/rating', $data);

        $I->expect('the request returned error');
        $I->seeResponseCodeIs(422);
    }

    public function testCreateWithNotValidaBusinessIdentifier(FunctionalTester $I): void
    {
        $entity = new Business();
        $I->persistEntity($entity, [
            'name'             => 'Acme',
            'constructionYear' => new \DateTime('2011-01-01'),
            'class'            => 1,
            'governmental'     => false,
        ]);

        $data = [
            'value' => 7,
        ];

        $I->wantTo('make sure the business will not be rated if the data is not valid');

        $I->amGoingTo('make a request to the business rating action ');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/v1/businesses/'.($entity->getId() + 1000).'/rating', $data);

        $I->expect('the request returned error');
        $I->seeResponseCodeIs(404);
    }
}
