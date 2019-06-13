<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Attribute;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\ProductFixtures;


class AttributeFixtures extends Fixture  implements DependentFixtureInterface
{
    private $faker;


    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadAttributes($manager);
    }

    public function loadAttributes(ObjectManager $manager)
    {
        for ($i = 1, $y = 1; $i < 250; $i++) {

            if ($i % 5 == 0) {
                $y++;
            }

            $attribute = new Attribute();
            $attribute->setName(ucfirst($this->faker->word)); // генерируем в faker значение для name
            $attribute->setValue(rtrim($this->faker->realText(30), ".")); // сеттим value
            $attribute->setProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE . $y));

            $manager->persist($attribute);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            ProductFixtures::class,
        );
    }
}
