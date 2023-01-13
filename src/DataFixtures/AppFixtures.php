<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\Configuration;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $userPasswordHasher;
    
    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        // Création d'un user "normal"
        $user = new User();
        $user->setEmail("user@bilemoapi.com");
        $user->setFullname("John Doe");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->userPasswordHasher->hashPassword( $user, "password"));
        $manager->persist( $user);
        
        // Création d'un user admin
        $userAdmin = new User();
        $userAdmin->setEmail("admin@bilemoapi.com");
        $userAdmin->setFullname("Daddy Daddy");
        $userAdmin->setRoles(["ROLE_ADMIN"]);
        $userAdmin->setPassword($this->userPasswordHasher->hashPassword($userAdmin, "password"));
        $manager->persist($userAdmin);

        

        
    
        for($i = 0; $i < 20 ; $i++){
            $image = new Image();
            $configuration = new Configuration();
            $product = new Product();
            $product->setName("mobile ". $i);
            $product->setScreen(0 .".".$i);
            $product->setHeight(0 .".".$i);
            $product->setWeight(0 .".".$i);
            $product->setWidth(0 .".".$i);
            $product->setLength( 0 .".".$i);
            $product->setVideo("video ".$i);
            $product->setWifi( true);
            $product->setCamera(true);
            $product->setBluetooth(true);
            $product->setDescription("Description ".$i);
            $product->setUserr($userAdmin);

            
            $configuration->setMemory("10.$i");
            $configuration->setPrice(100 .".". $i);
            $configuration->setColor("rouge noire $i");
            $configuration->setProduct($product);
            
             $image->setUrl("https://monImage $i");
             $image->setProduct($product);
            $manager->persist($configuration);
             $manager->persist($image);
             $manager->persist($product);
        }
       
        

       $manager->flush();
    
    }

    
}
