<?php

// src/OC/UserBundle/DataFixtures/ORM/LoadUser.php


namespace Okeanos\UserBundle\DataFixtures\ORM;


use Doctrine\Common\DataFixtures\FixtureInterface;

use Doctrine\Common\Persistence\ObjectManager;

use Okeanos\CoreBundle\Entity\Users;


class LoadUser implements FixtureInterface

{

  public function load(ObjectManager $manager)

  {

    // Les noms d'utilisateurs à créer

    $listNames = array('Alexandre');


    foreach ($listNames as $name) {

      // On crée l'utilisateur

      $user = new Users;


      // Le nom d'utilisateur et le mot de passe sont identiques pour l'instant

      $user->setUsername($name);

      $user->setPassword($name);

      $user->setEmail('test@gmail.com');


      // On ne se sert pas du sel pour l'instant

      $user->setSalt('');

      // On définit uniquement le role ROLE_USER qui est le role de base

      $user->setRoles(array('ROLE_ADMIN'));


      // On le persiste

      $manager->persist($user);

    }


    // On déclenche l'enregistrement

    $manager->flush();

  }

}