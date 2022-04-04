<?php

namespace App\Tests\Type;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Form\Test\TypeTestCase;

class UserTypeTest extends TypeTestCase
{
    public function testSubmitCreateForm()
    {
        $formData = [
            'username' => 'usernameTest',
            'password' => [
                'first' => 'passwordTest',
                'second' => 'passwordTest'
            ],
            'email' => 'username@test.com',
            'roles' => [
                'ROLE_USER'
            ]
        ];

        $user = new User();

        $form = $this->factory->create(UserType::class, $user, ['roles' => true]);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $userExpected = new User();
        $userExpected->setUsername('usernameTest');
        $userExpected->setPassword('passwordTest');
        $userExpected->setEmail('username@test.com');
        $userExpected->setRoles(['ROLE_USER']);

        $this->assertSame($userExpected->getUsername(), $user->getUsername());
        $this->assertSame($userExpected->getPassword(), $user->getPassword());
        $this->assertSame($userExpected->getEmail(), $user->getEmail());
        $this->assertSame($userExpected->getRoles(), $user->getRoles());
    }

    public function testSubmitEditForm()
    {
        $formData = [
            'username' => 'usernameTestWow',
            'password' => [
                'first' => 'passwordTestWow',
                'second' => 'passwordTestWow'
            ],
            'email' => 'username-wow@test.com',
            'roles' => [
                'ROLE_ADMIN'
            ]
        ];

        $user = new User();
        $user->setUsername('usernameTest');
        $user->setPassword('passwordTest');
        $user->setEmail('username@test.com');
        $user->setRoles(['ROLE_USER']);

        $form = $this->factory->create(UserType::class, $user, ['create' => false, 'roles' => true]);

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        $userExpected = new User();
        $userExpected->setUsername('usernameTestWow');
        $userExpected->setPassword('passwordTestWow');
        $userExpected->setEmail('username-wow@test.com');
        $userExpected->setRoles(['ROLE_ADMIN']);

        $this->assertSame($userExpected->getUsername(), $user->getUsername());
        $this->assertSame($userExpected->getPassword(), $form->get('password')->getData());
        $this->assertSame($userExpected->getEmail(), $user->getEmail());
        $this->assertSame($userExpected->getRoles(), $user->getRoles());
    }
}