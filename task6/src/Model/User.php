<?php

namespace App\Model;

/**
 * @Entity()
 * @Table(name="users")
 */
class User
{
    /**
     * @id
     * @Column(type="integer")
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="string", name="firstname", length=255, nullable=false)
     */
    public $firstname;

    /**
     * @Column(type="string", name="lastname", length=255, nullable=false)
     */
    public $lastname;

    /**
     * @Column(type="string", name="patronymic", length=255, nullable=true)
     */
    public $patronymic;

    /**
     * @Column(type="string", name="email", length=255, nullable=false)
     */
    public $email;

    /**
     * @Column(type="string", name="email", length=128, nullable=false)
     */
    public $password;
}