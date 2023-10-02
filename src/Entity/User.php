<?php

/**
 * Intersect CMS Unleashed
 * 2.5 Update
 * Last modify : 24/08/2021 at 20:21
 * Author : XFallSeane
 * Website : https://intersect.thomasfds.fr
 */

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Table(name: 'cms_users')]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[ORM\Entity(repositoryClass: UserRepository::class)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer", name: "webId")]
    private int $webid;

    #[ORM\Column(type: "text", length: 65535, nullable: false)]
    private string $id;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private string $username;

    #[ORM\Column(type: "text", length: 65535, nullable: false)]
    private string $email;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    private string $password;

    #[ORM\Column(type: "integer", nullable: false)]
    private int $points = 0;

    #[ORM\Column(type: "integer", nullable: false)]
    private int $admin = 0;

    #[ORM\Column(type: "string", length: 255, nullable: true, name: "password_token")]
    private ?string $passwordToken;

    #[ORM\Column(type: "json")]
    private array $roles = [];


    /**
     * Get the value of webid
     *
     * @return  int
     */
    public function getWebid()
    {
        return $this->webid;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * Set the value of webid
     *
     * @param  int  $webid
     *
     * @return  self
     */
    public function setWebid(int $webid)
    {
        $this->webid = $webid;

        return $this;
    }

    /**
     * Get the value of passwordToken
     *
     * @return  string|null
     */
    public function getPasswordToken()
    {
        return $this->passwordToken;
    }

    /**
     * Set the value of passwordToken
     *
     * @param  string|null  $passwordToken
     *
     * @return  self
     */
    public function setPasswordToken($passwordToken)
    {
        $this->passwordToken = $passwordToken;

        return $this;
    }

    /**
     * Get the value of admin
     *
     * @return  int
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    /**
     * Set the value of admin
     *
     * @param  int  $admin
     *
     * @return  self
     */
    public function setAdmin(int $admin)
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * Get the value of points
     *
     * @return  int
     */
    public function getPoints()
    {
        return $this->points;
    }

    /**
     * Set the value of points
     *
     * @param  int  $points
     *
     * @return  self
     */
    public function setPoints(int $points)
    {
        $this->points = $points;

        return $this;
    }


    /**
     * Get the value of password
     *
     * @return  string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     *
     * @return  self
     */
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of email
     *
     * @return  string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of username
     *
     * @return  string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param  string  $username
     *
     * @return  self
     */
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of id
     *
     * @return  string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param  string  $id
     *
     * @return  self
     */
    public function setId(string $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials():void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }
}
