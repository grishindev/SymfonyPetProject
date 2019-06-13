<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Cocur\Slugify\Slugify;
use Faker\Factory;
use App\Entity\ContactUs;


class ContactUsFixtures extends Fixture
{
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
        for ($i = 1; $i < 31; $i++) {
            $contact_us = new ContactUs();
            $contact_us->setThema(rtrim($this->faker->realText(50), "."));
            $contact_us->setBody($this->faker->realText(300, 2));
            $contact_us->setAuthorName($this->faker->name);
            $contact_us->setAuthorEmail($this->faker->email);
            $contact_us->setStatus(strtolower($this->faker->word));
            $contact_us->setCreationDate($this->faker->dateTimeBetween('-6 years', 'now'));

            $manager->persist($contact_us);
        }

        $manager->flush();
    }
}
