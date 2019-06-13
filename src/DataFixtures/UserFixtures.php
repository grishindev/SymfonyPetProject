<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cocur\Slugify\Slugify;
use Faker\Factory;
use App\Entity\User;


class UserFixtures extends Fixture
{
    public const USER_REFERENCE = 'user';

    private $faker;

    private $slug;

    public function __construct(Slugify $slugify)
    {
        $this->faker = Factory::create();
        $this->slug = $slugify;
    }

    public function load(ObjectManager $manager)
    {
        $this->loadUsers($manager);
    }

    public function loadUsers(ObjectManager $manager)
    {
        for ($i = 1; $i < 41; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setDob($this->faker->dateTimeBetween('-50 years', '-10 years'));
            $user->setRole(rtrim(ucfirst(strtolower($this->faker->realText(10, 2))), "."));
            $user->setPhone($this->faker->phoneNumber);
            $user->setEmail($this->faker->email);
            $user->setPassword($this->faker->password);
            $user->setZip($this->faker->postcode);
            $user->setCountry($this->faker->country);
            $user->setRegion(ucfirst($this->faker->word));
            $user->setCity($this->faker->city);
            $user->setAddress($this->faker->address);
            $user->setCreationDate($this->faker->dateTimeBetween('-6 years', 'now'));

            $this->addReference(self::USER_REFERENCE . $i, $user);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
