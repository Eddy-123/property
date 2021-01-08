<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

use App\Entity\User;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;
    
    public function __construct(UserPasswordEncoderInterface $userPasswordEncoder) {
        $this->userPasswordEncoder = $userPasswordEncoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $user = new User();
        
        $user->setEmail("demo@gmail.com");
        $user->setPassword($this->userPasswordEncoder->encodePassword(
                $user,
                "demo"
            ));
        
        $manager->persist($user);
        $manager->flush();
    }
}
