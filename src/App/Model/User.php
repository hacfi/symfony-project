<?php

namespace App\Model;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation as Serialize;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users", uniqueConstraints={@ORM\UniqueConstraint(name="email_unique", columns={"email"})})
 * @ORM\HasLifecycleCallbacks
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 *
 * @UniqueEntity(fields={"email"}, groups={"registration"})
 *
 * @Serialize\ExclusionPolicy("all")
 */
class User implements UserInterface
{
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=36)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="App\Util\ORM\UuidGenerator")
     *
     * @Serialize\Expose
     * @Serialize\Groups({"default", "public_list", "private_list", "public_item", "private_item"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     *
     * @Assert\Email()
     *
     * @Serialize\Expose
     * @Serialize\Groups({"private_list", "private_item"})
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    private $admin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Serialize\Expose
     * @Serialize\Groups({"default", "public_list", "private_list", "public_item", "private_item"})
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     *
     * @Serialize\Expose
     * @Serialize\Groups({"private_list", "private_item"})
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=60, nullable=true)
     *
     * @Assert\NotBlank(groups={"registration"})
     */
    private $password;


    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->admin = false;
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }


    /*
     * Implement UserInterface methods
     */

    public function getRoles()
    {
        $roles = ['ROLE_USER'];

        if ($this->isAdmin()) {
            array_unshift($roles, 'ROLE_ADMIN');
        }

        return $roles;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function eraseCredentials()
    {
    }

    /*
     * Getters & setters
     */

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param string $email
     * @return $this self Object
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }


    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->admin;
    }


    /**
     * @param \DateTime|null $createdAt
     * @return $this self Object
     */
    public function setCreatedAt($createdAt = null)
    {
        $this->createdAt = $createdAt instanceof \DateTime ? $createdAt : new \DateTime();

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime|null $updatedAt
     * @return $this self Object
     *
     * @ORM\PreUpdate
     */
    public function setUpdatedAt($updatedAt = null)
    {
        $this->updatedAt = $updatedAt instanceof \DateTime ? $updatedAt : new \DateTime();

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $password
     * @return $this self Object
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
}
