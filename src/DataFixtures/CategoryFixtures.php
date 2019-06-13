<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Cocur\Slugify\Slugify;
use App\Entity\Category;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_REFERENCE = 'category';

    private $faker;

    private $slug;

    public function __construct(Slugify $slugify)
    {
        $this->faker = Factory::create();
        $this->slug = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadCategories($manager);
    }

    public function loadCategories(ObjectManager $manager)
    {
        for ($i = 1; $i < 11; $i++) {



            $category = new Category();
            $category->setName($this->faker->realText(100));
            $category->setSlug($this->slug->slugify(mb_strimwidth($category->getName(), 0, 40)));

            $this->addReference(self::CATEGORY_REFERENCE . $i, $category);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
