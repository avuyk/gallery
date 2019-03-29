<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\File;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends BaseFixture
{
    // Listing of categories to be created
    // Names must correspond with categories from http://lorempixel.com
    private static $categoryNames = [
        'abstract',
        'animals',
        'business',
        'cats',
        'city',
        'food',
        'people',
        'nature',
        'sports',
        'technics',
        'transport',
    ];

    private $currentCategory;

    // Fill database with dummy data
    public function loadData(ObjectManager $manager)
    {
        // Create categories and create images in all categories
        foreach (self::$categoryNames as $categoryName)
        {
            $this->currentCategory = $categoryName;
            $this->createManyWithAssociatedClass(File::class, Category::class, 30,
                function (File $file, Category $category, $count) use ($manager)
                {
                    $file->setFilePath($this->faker->image('public' . DIRECTORY_SEPARATOR . 'images' . DIRECTORY_SEPARATOR . 'gallery',
                        $width = 640, $height = 480, $this->currentCategory, false))
                        ->setFileName($this->faker->word . ' '.$this->currentCategory)
                        ->setDescription($this->faker->sentence($nbWords = 12, $variableNbWords = true));
                    $category->setCategoryName($this->currentCategory);
                    // add an association from current Category object to current File object
                    $file->addCategory($category);
                });
        }
        $manager->flush();
    }
}
