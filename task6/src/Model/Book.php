<?php

namespace App\Model;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;

use Symfony\Component\Validator\Constraints as Assert;

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
     * @Column(type="integer")
     * #[Assert\NotBlank]
     */
    public $user_id;

    /**
     * @Column(type="integer")
     * #[Assert\NotBlank]
     */
    public $author_id;

    /**
     * @Column(type="boolean")
     * #[Assert\NotBlank]
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

    public function getUser()
    {
        return app()->orm->getModelManager()
            ->getRepository(User::class)
            ->find($this->author_id);
    }

    public function getAuthor()
    {
        return app()->orm->getModelManager()
            ->getRepository(Author::class)
            ->find($this->author_id);
    }
}