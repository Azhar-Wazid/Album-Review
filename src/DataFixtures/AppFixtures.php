<?php

namespace App\DataFixtures;

use App\Entity\Album;
use App\Entity\Genre;
use App\Entity\Review;
use App\Entity\Track;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        // $product = new Product();
        // $manager->persist($product);
        $users = [];

        $admin = new User();
        $admin->setUsername('admin');
        $admin->setEmail('admin@example.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('admin123');
        $manager->persist($admin);
        $users[] = $admin;

        $mod = new User();
        $mod->setUsername('moderator');
        $mod->setEmail('mod@example.com');
        $mod->setRoles(['ROLE_MOD']);
        $mod->setPassword('mod123');
        $manager->persist($mod);
        $users[] = $mod;

        $user1 = new User();
        $user1->setUsername('user1');
        $user1->setEmail('user1@example.com');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword('user123');
        $manager->persist($user1);
        $users[] = $user1;

        $user2 = new User();
        $user2->setUsername('user2');
        $user2->setEmail('user2@example.com');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword('user123');
        $manager->persist($user2);
        $users[] = $user2;

        $genreNames = ['Rock', 'Pop', 'Hip-Hop', 'Electronic', 'Jazz', 'Metal'];
        $genres = [];

        foreach ($genreNames as $name) {
            $genre = new Genre();
            $genre->setGenreName($name);
            $manager->persist($genre);
            $genres[] = $genre;
        }

        for ($i = 1; $i <= 10; $i++) {

            $album = new Album();
            $album->setAlbumName($faker->sentence(3));
            $album->setUserID($faker->randomElement($users));
            $album->addGenreID($faker->randomElement($genres));
            $album->setReleaseDate($faker->numberBetween(1950, 2025));

            $manager->persist($album);

            $trackCount = rand(5, 12);

            for ($t = 1; $t <= $trackCount; $t++) {
                $track = new Track();
                $track->setAlbumID($album);
                $track->setTrackNumber($t);
                $track->setTitle($faker->sentence(2));
                $track->setDuration(rand(90, 360)); // length in seconds
                $manager->persist($track);
            }


            $reviewCount = rand(1, 6);

            for ($r = 1; $r <= $reviewCount; $r++) {
                $review = new Review();
                $review->setAlbumID($album);
                $review->setUserID($faker->randomElement($users));
                $review->setReviewScore(rand(1, 5));
                $review->setReviewDetails($faker->paragraph());
                $manager->persist($review);
            }
        }

        $manager->flush();
    }
}
