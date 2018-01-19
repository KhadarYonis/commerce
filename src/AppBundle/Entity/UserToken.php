<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserToken
 *
 * @ORM\Table(name="user_token")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserTokenRepository")
 */
class UserToken
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
     * @ORM\Column(name="user_email", type="string", length=50)
     */
    private $userEmail;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="text")
     */
    private $token;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration_date", type="datetime")
     */
    private $expirationDate;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userEmail
     *
     * @param string $userEmail
     *
     * @return UserToken
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;

        return $this;
    }

    /**
     * Get userEmail
     *
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * Set token
     *
     * @param string $token
     *
     * @return UserToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set expirationDate
     *
     * @param \DateTime $expirationDate
     *
     * @return UserToken
     */
    public function setExpirationDate($expirationDate)
    {
        $this->expirationDate = $expirationDate;

        return $this;
    }

    /**
     * Get expirationDate
     *
     * @return \DateTime
     */
    public function getExpirationDate()
    {
        return $this->expirationDate;
    }
}

