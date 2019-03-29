<?php


namespace App\DataFixtures;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

abstract class BaseFixture extends Fixture
{
    /* @var \Doctrine\Common\Persistence\ObjectManager */
    private $manager;

    /* @var \Faker\Generator */
    protected $faker;

    abstract protected function loadData(ObjectManager $em);

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;
        $this->faker = Factory::create();
        $this->loadData($manager);
    }

    protected function createManyWithAssociatedClass(string $className, string $associatedClassName, int $count, callable $factory)
    {
        $associatedEntity = new $associatedClassName();
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();
            $factory($entity, $associatedEntity, $i);

            $this->manager->persist($entity);
            $this->manager->persist($associatedEntity);
        }
    }

}