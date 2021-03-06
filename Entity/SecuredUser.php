<?php

namespace Youshido\SecurityUserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class SecuredUser
 *
 * @ORM\HasLifecycleCallbacks()
 * @ORM\MappedSuperclass(repositoryClass="Youshido\SecurityUserBundle\Entity\Repository\SecuredUserRepository")
 * @UniqueEntity("email")
 * @property mixed $id
 * @method integer getId()
 */
class SecuredUser implements \Serializable, AdvancedUserInterface
{

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;

    /**
     * @var string
     *
     * @Assert\Email()
     * @ORM\Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    protected $active = false;

    /**
     * @var boolean
     *
     * @ORM\Column(name="enable", type="boolean")
     */
    protected $enable = true;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable = true)
     */
    protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="activation_code", type="string", nullable=true)
     */
    protected $activationCode;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="activated_at", type="datetime", nullable=true)
     */
    protected $activatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     * @return SecuredUser
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getFullName()
    {
        return $this->firstName . ($this->lastName && $this->firstName ? ' ' : '') . $this->lastName;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return SecuredUser
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return SecuredUser
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return SecuredUser
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return SecuredUser
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate()
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @inheritdoc
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isAccountNonLocked()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isCredentialsNonExpired()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function isEnabled()
    {
        return $this->getActive() && $this->isEnable();
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return SecuredUser
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return boolean
     */
    public function isEnable()
    {
        return $this->enable;
    }

    /**
     * @param boolean $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }

    /**
     * @inheritdoc
     */
    public function serialize()
    {
        return serialize([
            $this->id,
            $this->email,
            $this->password
        ]);
    }

    /**
     * @inheritdoc
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized);
    }

    /**
     * @inheritdoc
     */
    public function getRoles()
    {
        return ['ROLE_USER'];
    }

    /**
     * @inheritdoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getUsername()
    {
        return $this->getEmail();
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return SecuredUser
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function eraseCredentials()
    {

    }

    /**
     * @return \DateTime
     */
    public function getActivatedAt()
    {
        return $this->activatedAt;
    }

    /**
     * @param \DateTime $activatedAt
     * @return SecuredUser
     */
    public function setActivatedAt($activatedAt)
    {
        $this->activatedAt = $activatedAt;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getActivationCode()
    {
        return $this->activationCode;
    }

    /**
     * @param mixed $activationCode
     * @return SecuredUser
     */
    public function setActivationCode($activationCode)
    {
        $this->activationCode = $activationCode;

        return $this;
    }

}
