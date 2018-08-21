<?php
declare(strict_types = 1);

namespace App\Tests\Dto\Assembler;

use App\Dto\Assembler\BusinessAssembler;
use App\Entity;
use App\Dto\Request;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;

class BusinessAssemblerTest extends Unit
{
    public function testNormalizeDto(): void
    {
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->willReturnCallback(function (int $id, string $className) {
                if ($className === Entity\Business::class) {
                    return (new Entity\Business())
                        ->setId($id)
                        ->setName('Acme')
                        ->setConstructionYear(new \DateTime('2005-06-23'))
                        ->setClass(2)
                        ->setGovernmental(false)
                        ->setCreatedAt(new \DateTimeImmutable());
                }

                return null;
            });

        $dto = new Request\Business();
        $dto
            ->setId(1)
            ->setGovernmental(true);

        /** @var EntityManagerInterface $entityManager */
        $assembler = new BusinessAssembler($entityManager);
        $assembler->normalizeDto($dto);

        // Id should not be changed.
        $this->assertEquals(1, $dto->getId());

        // The property values should be changed in the stage of object deserialization.
        $this->assertTrue($dto->isPropertyChanged('governmental'));
        $this->assertEquals(true, $dto->isGovernmental());

        // The property values should not be changed,
        // and all missing property values should be loaded.
        $this->assertFalse($dto->isPropertyChanged('name'));
        $this->assertEquals('Acme', $dto->getName());
        $this->assertFalse($dto->isPropertyChanged('class'));
        $this->assertEquals(2, $dto->getClass());
        $this->assertFalse($dto->isPropertyChanged('constructionYear'));
        $this->assertEquals(2005, $dto->getConstructionYear());
    }

    public function testWriteEntity(): void
    {
        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->getMock();
        $entityManager
            ->expects($this->once())
            ->method('find')
            ->willReturnCallback(function (int $id, string $className) {
                if ($className === Entity\Business::class) {
                    return (new Entity\Business())
                        ->setId($id)
                        ->setName('Acme')
                        ->setConstructionYear(new \DateTime('2013-03-03'))
                        ->setClass(3)
                        ->setGovernmental(true)
                        ->setCreatedAt(new \DateTimeImmutable());
                }

                return null;
            });

        $dto = new Request\Business();
        $dto
            ->setId(3)
            ->setName('New Acme')
            ->setConstructionYear(2000);

        /** @var EntityManagerInterface $entityManager */
        $assembler = new BusinessAssembler($entityManager);
        $entity = $assembler->writeEntity($dto);

        // Id should not be changed.
        $this->assertEquals(3, $entity->getId());

        // The property values received from DTO.
        $this->assertEquals($dto->getName(), $entity->getName());
        $this->assertEquals($dto->getConstructionYear(), (int) $entity->getConstructionYear()->format('Y'));

        // The property values received from database.
        $this->assertEquals(3, $entity->getClass());
        $this->assertEquals(true, $entity->isGovernmental());
        $this->assertNotNull($entity->getCreatedAt());
    }

    public function testWriteNewEntity(): void
    {
        $dto = new Request\Business();
        $dto
            ->setName('New Acme')
            ->setConstructionYear(2000)
            ->setClass(3)
            ->setGovernmental(false);

        /** @var EntityManagerInterface $entityManager */
        $assembler = new BusinessAssembler($entityManager);
        $entity = $assembler->writeEntity($dto);

        // The property values received from DTO.
        $this->assertEquals($dto->getName(), $entity->getName());
        $this->assertEquals($dto->getConstructionYear(), (int) $entity->getConstructionYear()->format('Y'));
        $this->assertEquals($dto->getName(), $entity->getName());
        $this->assertEquals($dto->getConstructionYear(), (int) $entity->getConstructionYear()->format('Y'));
        $this->assertEquals($dto->getClass(), $entity->getClass());
        $this->assertEquals($dto->isGovernmental(), $entity->isGovernmental());
    }

    public function testWriteDto(): void
    {
        $entity = new Entity\Business();
        $entity
            ->setId(2)
            ->setName('Acme')
            ->setConstructionYear(new \DateTime('2015-12-05'))
            ->setClass(1)
            ->setGovernmental(true)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTime());

        $assembler = new BusinessAssembler(null);
        $dto = $assembler->writeDto($entity);

        $this->assertEquals($entity->getId(), $dto->getId());
        $this->assertEquals($entity->getName(), $dto->getName());
        $this->assertEquals((int) $entity->getConstructionYear()->format('Y'), $dto->getConstructionYear());
        $this->assertEquals($entity->getClass(), $dto->getClass());
        $this->assertEquals($entity->isGovernmental(), $dto->isGovernmental());
        $this->assertEquals($entity->getCreatedAt(), $dto->getCreatedAt());
        $this->assertEquals($entity->getUpdatedAt(), $dto->getUpdatedAt());
    }
}
