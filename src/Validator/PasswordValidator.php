<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class PasswordValidator
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * Valide les contraintes de sécurité d'un mot de passe brut.
     *
     * @param string|null $password
     * @return ConstraintViolationListInterface
     */
    public function validate(?string $password): ConstraintViolationListInterface
    {
        return $this->validator->validate($password, [
            new Assert\NotBlank([
                'message' => 'Le mot de passe ne peut pas être vide.',
            ]),
            new Assert\Length([
                'min' => 12,
                'max' => 100,
                'minMessage' => 'Votre mot de passe doit comporter au minimum {{ limit }} caractères',
            ]),
            new Assert\Regex([
                'pattern' => '/^(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()+,.?:{}|<>\/]).{12,}$/',
                'message' => 'Le mot de passe doit contenir au minimum une lettre majuscule, un chiffre et un caractère spécial',
            ]),
        ]);
    }
}
