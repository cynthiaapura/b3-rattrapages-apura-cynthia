<?php

namespace App\DataFixtures;

use App\Entity\Product;          
use Doctrine\Bundle\FixturesBundle\Fixture; 
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;                         

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();

            $product->setName($faker->words(3, true));
            $product->setImage($faker->imageUrl(640, 480, 'food'));
            $product->setDescription($faker->paragraph(2));
            $product->setPrice($faker->randomFloat(2, 1, 100));
            $product->setRating($faker->randomFloat(1, 0, 5));
            $product->setAvailable($faker->boolean(80));

            $manager->persist($product);
        }

        $manager->flush();
    }
}
