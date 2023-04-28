<?php

namespace App\Model;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;

/**
 * @Entity
 * @Table(name="users")
 */
class User
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    public $id;

    /**
     * @Column(type="string")
     */
    public $firstname;

    /**
     * @Column(type="string")
     */
    public $lastname;

    /**
     * @Column(type="string", nullable=true)
     */
    public $patronymic;

    /**
     * @Column(type="string")
     */
    public $email;

    /**
     * @Column(type="string")
     */
    public $password;
}