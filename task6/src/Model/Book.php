<?php

namespace App\Model;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

use App\Model\User;
use App\Model\Author;

/**
 * @Entity
 * @Table(name="books")
 */
class Book
{
    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    public $id;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="User", inversedBy="books")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user_id;

    /**
     * @var User
     *
     * @ManyToOne(targetEntity="Author", inversedBy="books")
     * @JoinColumn(name="author_id", referencedColumnName="id")
     */
    public $author_id;

    /**
     * @Column(type="boolean")
     */
    public $isdownload;

    /**
     * @Column(type="string")
     */
    public $picture_path;

    /**
     * @Column(type="string")
     */
    public $file_path;

    /**
     * @Column(type="date")
     */
    public $date;

    /**
     * @Column(type="string")
     */
    public $name;
}