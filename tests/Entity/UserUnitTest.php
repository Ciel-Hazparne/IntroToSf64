<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserUnitTest extends TestCase
{
    private function createUser(): User
    {
        return new User()
            ->setEmail('a@ciel.eh')
            ->setFirstname('a')
            ->setLastname('IR')
            ->setPassword('/Ciel 64240/');
    }

    public function testUserGettersReturnExpectedValues(): void
    {
        $user = $this->createUser();

        $this->assertSame('a@ciel.eh', $user->getEmail());
        $this->assertSame('a', $user->getFirstname());
        $this->assertSame('IR', $user->getLastname());
        $this->assertSame('/Ciel 64240/', $user->getPassword());
    }

    public function testUserGettersReturnFalseForWrongValues(): void
    {
        $user = $this->createUser();

        $this->assertNotSame('wrong@ciel.eh', $user->getEmail());
        $this->assertNotSame('wrong', $user->getFirstname());
        $this->assertNotSame('wrong', $user->getLastname());
        $this->assertNotSame('000000000000', $user->getPassword());
    }

    public function testNewUserIsEmpty(): void
    {
        $user = new User();

        $this->assertEmpty($user->getEmail());
        $this->assertEmpty($user->getFirstname());
        $this->assertEmpty($user->getLastname());
        $this->assertEmpty($user->getPassword());

    }
}
