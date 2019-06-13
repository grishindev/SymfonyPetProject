<?php

namespace App\DataFixtures;

use App\Entity\Review;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\DataFixtures\UserFixtures;
use App\DataFixtures\ProductFixtures;


class ReviewFixtures extends Fixture  implements DependentFixtureInterface
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
        $this->loadReviews($manager);
    }

    public function loadReviews(ObjectManager $manager)
    {
        for ($i = 1, $y = 1, $z = 1; $i < 200; $i++) {

            if ($i % 5 == 0) {
                $y++;
            }

            if ($i % 8 == 0) {
                $z++;
            }

            $review = new Review();
            $review->setTitle(rtrim($this->faker->realText(50), "."));
            $review->setBody($this->faker->realText(300));
            $review->setUser($this->getReference(UserFixtures::USER_REFERENCE . $y));
            $review->setProduct($this->getReference(ProductFixtures::PRODUCT_REFERENCE . $z));
            $review->setStars(random_int(1, 5));
            $review->setCreationDate($this->faker->dateTimeBetween('-6 years', 'now'));
            $review->setSlug($this->slug->slugify(mb_strimwidth($review->getTitle(), 0, 40)));

            $manager->persist($review);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            ProductFixtures::class,
        );
    }
}
