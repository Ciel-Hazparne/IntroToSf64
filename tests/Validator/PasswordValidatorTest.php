<?php

namespace App\Tests\Validator;

use App\Validator\PasswordValidator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PasswordValidatorTest extends KernelTestCase
{
    private PasswordValidator $validator;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $this->validator = new PasswordValidator($container->get(ValidatorInterface::class));
    }

    public function testPasswordConforme(): void
    {
        $password = 'SuperSecure123!';
        $violations = $this->validator->validate($password);

        $this->assertCount(
            0,
            $violations,
            "Le mot de passe conforme '$password' a généré des violations alors qu'il respecte toutes les contraintes."
        );
    }

    public function testPasswordTropCourt(): void
    {
        $password = 'Ab1!';
        $violations = $this->validator->validate($password);

        $this->assertGreaterThan(
            0,
            count($violations),
            "Le mot de passe trop court '$password' n’a généré aucune violation alors qu’il devrait."
        );
    }

    public function testPasswordSansMajuscule(): void
    {
        $password = 'password123!';
        $violations = $this->validator->validate($password);

        $this->assertGreaterThan(
            0,
            count($violations),
            "Le mot de passe sans majuscule '$password' n’a généré aucune violation alors qu’il devrait."
        );
    }

    public function testPasswordSansChiffre(): void
    {
        $password = 'Password!';
        $violations = $this->validator->validate($password);

        $this->assertGreaterThan(
            0,
            count($violations),
            "Le mot de passe sans chiffre '$password' n’a généré aucune violation alors qu’il devrait."
        );
    }

    public function testPasswordSansCaractereSpecial(): void
    {
        $password = 'Password123';
        $violations = $this->validator->validate($password);

        $this->assertGreaterThan(
            0,
            count($violations),
            "Le mot de passe sans caractère spécial '$password' n’a généré aucune violation alors qu’il devrait."
        );
    }
}