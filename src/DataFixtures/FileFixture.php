<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\ImageFile;
use Doctrine\Common\Persistence\ObjectManager;

class FileFixture extends BaseFixture
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

    /**
     * Fill database with dummy data
     * @param ObjectManager $manager
     */
    public function loadData(ObjectManager $manager): void
    {
        // Create categories and create images in all categories
        foreach (self::$categoryNames as $categoryName) {
            // get a random number that is biased towards the lower of two given numbers
            $number = $this->faker->biasedNumberBetween(2, 12, function ($x) {
                return 1 - sqrt($x);
            });

            $this->createManyWithAssociatedClass(
                ImageFile::class,
                Category::class,
                $number,
                function (ImageFile $file, Category $category, $count) use ($manager, $categoryName) {
                    $file->setImageFileName($this->faker->image(
                        'public/images/gallery',
                        $width = 640,
                        $height = 480,
                        $categoryName,
                        false
                    ))
                        ->setImageFileTitle($this->faker->word)
                        ->setImageFileDescription($this->faker->sentence($nbWords = 12, $variableNbWords = true))
                        ->setUpdatedAt($this->faker->dateTimeBetween('-3 months', 'now'));
                    $category->setCategoryName($categoryName);
                    $file->addCategory($category);
                }
            );
        }
        $manager->flush();
    }
}
