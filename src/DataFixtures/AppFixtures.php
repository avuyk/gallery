<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private static $categoryNames = [
        'Cats',
        'Flowers',
        'Flags',
        'Space ships',
        'Portraits',
        'Dogs',
        'Trees',
        'Countries',
        'Icons',
        'Holidays',
    ];
    private static $fileNames = [
        // TODO: list the names
    ];
    private static $filePaths = [
        // TODO: list the paths
    ];
    private static $descriptions = [
        // TODO: list the descriptions
    ];
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
}
