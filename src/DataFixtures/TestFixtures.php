<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Admin;
use App\Entity\Conference;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager) {
        $admin = new Admin();
        $admin
            ->setUsername('testadmin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'admin'));
        $manager->persist($admin);

        $teror = new Conference();
        $teror->setCity('Teror Test')
            ->setYear('2022')
            ->setIsInternational(false);
        $manager->persist($teror);

        $arucas = new Conference();
        $arucas->setCity('Arucas Test')
            ->setYear('2017')
            ->setIsInternational(true);
        $manager->persist($arucas);

        $commentJuan = new Comment();
        $commentJuan->setConference($teror)
            ->setAuthor('Juan Test')
            ->setEmail('juantest@gmail.com')
            ->setText('Amazing test bro')
            ->setState('published');
        $manager->persist($commentJuan);

        $commentPedro = new Comment();
        $commentPedro->setConference($arucas)
            ->setAuthor('Pedro Test')
            ->setEmail('pedrotest@gmail.com')
            ->setText('So nice test bro');
        $manager->persist($commentPedro);

        $manager->flush();
    }
}
