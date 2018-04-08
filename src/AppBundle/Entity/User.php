<?php

namespace AppBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={
 *      @ORM\Index(name="email", columns={"email"})
 * })
 * @ORM\Entity
 */
class User implements UserInterface
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=1024)
     */
    private $email;

    /**
     * @var string
     * 
     * @ORM\Column(name="password_hash", type="string", length=255)
     */
    private $passwordHash;
    
    /**
     * @var string[]
     * @ORM\Column(name="roles", type="simple_array")
     */
    private $roles;
    
    function getId()
    {
        return $this->id;
    }
    
    public function eraseCredentials()
    {
        
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        
    }

    public function getUsername(): string
    {
        return $this->email;
    }

}
