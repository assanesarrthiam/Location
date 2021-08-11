<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Image;
use App\Entity\Anounce;
use App\Entity\Comment;
use Cocur\Slugify\Slugify;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $em)
    {


        $faker = Factory::create('fr_FR');
        $slugger = new Slugify();

        for ($i = 0; $i < 5; $i++) {
            $anounce = new Anounce();
            $anounce->setTitle($faker->sentence(3, false))
                ->setDesription($faker->text(200))
                ->setPrice(mt_rand(30000, 60000))
                ->setAddress($faker->address())
                ->setCoverImage("https://picsum.photos/seed/picsum/200/300")
                ->setRooms(mt_rand(0, 5))
                ->setIsAvailable(mt_rand(0, 1))
                ->setCreatedAt($faker->dateTimeBetween('-3 month', 'now'));

            for ($j = 0; $j < mt_rand(0, 7); $j++) {
                $comment = new Comment();
                $comment->setAuthor($faker->name())
                    ->setMail($faker->email())
                    ->setContent($faker->text(200))
                    ->setCreatedAt($faker->dateTimeBetween('-3 month', 'now'))
                    ->setAnounce($anounce);
                $em->persist($comment);
                $anounce->addComment($comment);
            }
            for ($k = 0; $k < mt_rand(0, 7); $k++) {
                $image = new Image();
                $image->setImageURL("https://picsum.photos/seed/picsum/200/300"  . mt_rand(1, 5000));
                $image->setDescription($faker->sentence());
                $anounce->addImage($image);
                $em->persist($image);
            }
            $em->persist($anounce);
        }
        $em->flush();
    }
}

