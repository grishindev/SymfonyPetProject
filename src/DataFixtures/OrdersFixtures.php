<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use App\Entity\Orders;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;


class OrdersFixtures extends Fixture  implements DependentFixtureInterface
{
    private $faker;


    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function load(ObjectManager $manager)
    {
        $this->loadOrders($manager);
    }

    public function loadOrders(ObjectManager $manager)
    {
        for ($i = 1, $y = 1; $i < 90; $i++) {

            if ($i % 3 == 0) {
                $y++;
            }

            $order = new Orders();
            $order->setUser($this->getReference(UserFixtures::USER_REFERENCE . $y));
            $order->setStatus(rtrim(ucfirst(strtolower($this->faker->realText(10, 2))), "."));
            $order->setCreationDate($this->faker->dateTimeBetween('-6 years', 'now'));

            $manager->persist($order);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
        );
    }
}
