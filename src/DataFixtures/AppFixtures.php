<?php

namespace App\DataFixtures;

use App\Entity\Contract;
use App\Entity\Customer;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
      $factory = new Factory();
      $faker = $factory::create("fr-FR");

      for ($u=0; $u <5;$u++){

          $user = new User();
          $password = $this->encoder->encodePassword($user,"Password");
          $user->setEmail($faker->email)
              ->setFirstname($faker->firstName)
              ->setLastname($faker->lastName)
              ->setRoles(["ROLE_USER"])
              ->setPassword($password);
          $manager->persist($user);

          for ($c = 0; $c<30; $c++){
              $customer = new Customer();
              $customer->setFirstname($faker->firstName)
                  ->setLastname($faker->lastName)
                  ->setEmail($faker->email)
                  ->setBirthday($faker->dateTimeBetween("-50year"))
                  ->setCompany("shconseils")
                  ->setAddress($faker->streetName)
                  ->setStreetNumber(mt_rand(1,20))
                  ->setNpa(mt_rand(01000,75000))
                  ->setCity($faker->city)
                  ->setCountry("France")
                  ->setCivility($faker->randomElement(["M","Mme"]))
                  ->setUser($user);
              $manager->persist($customer);

              for ($con = 0;$con<3;$con++){
                  $contract = new Contract();
                  $contract->setCompany($faker->randomElement(["APRIL","NEOLIANE","MALAKOFF"]))
                      ->setCa($faker->randomFloat(2,1200,3000))
                      ->setCommission($faker->randomFloat(2,50,800))
                      ->setCustomer($customer)
                      ->setDocuments('1JfiBtAWriVAsLMzIo0gms4DUtisLx9Gg')
                      ->setContractSignedAt($faker->dateTimeBetween("-2 years"))
                      ->setStartAt($faker->dateTimeBetween("-1 years"))
                      ->setEndAt($faker->dateTimeBetween('-1 years'))
                      ->setPrime($faker->randomFloat(2,56,230))
                      ->setStatus($faker->randomElement(['PROCESSING','PAYED','CANCELED','DISCOUNTED']))
                      ->setType($faker->randomElement(['SANTE','GAV','PREVOYANCE','OBSEQUE']));

                  $manager->persist($contract);
              }

          }
          $manager->flush();
      }



    }
}
