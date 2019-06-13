<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cocur\Slugify\Slugify;
use Faker\Factory;
use App\Entity\Product;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public const PRODUCT_REFERENCE = 'product';

    private $faker;

    private $slug;

    public function __construct(Slugify $slugify)
    {
        $this->faker = Factory::create();
        $this->slug = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadProducts($manager);
    }

    public function loadProducts(ObjectManager $manager)
    {
        for ($i = 1, $y = 1; $i < 51; $i++) {

            if ($i % 6 == 0) {
                $y++;
            }

            $product = new Product();
            $product->setName($this->faker->realText(100)); // генерируем в faker значение для name
            $product->setPrice($this->faker->randomFloat(2, 1, 999)); // сеттим price
            $product->setStatus(rtrim(ucfirst(strtolower($this->faker->realText(10, 2))), ".")); // сеттим status
            $product->setSlug($this->slug->slugify(mb_strimwidth($product->getName(), 0, 40)));  // генерируем slug с помощью метода slugify класса Slugify, куда передаем name с помощью метода getName()
            $product->addCategory($this->getReference(CategoryFixtures::CATEGORY_REFERENCE . $y));

            $this->addReference(self::PRODUCT_REFERENCE . $i, $product);
            $manager->persist($product);
        }

        $manager->flush();
    }


    public function getDependencies()
    {
        return array(
            CategoryFixtures::class,
        );
    }
}
