<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

abstract class BaseFixture extends Fixture
{
    /* @var ObjectManager */
    private $manager;

    /* @var Generator */
    protected $faker;

    abstract protected function loadData(ObjectManager $doctrineObjectManager);

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData($manager);
    }

    protected function createManyWithAssociatedClass(
        string $className,
        string $associatedClassName,
        int $count,
        callable $factory
    ): void {
        $associatedEntity = new $associatedClassName();
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $associatedEntity, $i);

            $this->manager->persist($entity);
            $this->manager->persist($associatedEntity);
        }
    }
}
