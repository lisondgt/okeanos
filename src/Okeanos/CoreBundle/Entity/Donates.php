<?php // src/Okeanos/CoreBundle/Entity/Donates.php

namespace Okeanos\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Donates
 *
 * @ORM\Table(name="donates")
 * @ORM\Entity(repositoryClass="Okeanos\CoreBundle\Repository\DonatesRepository")
 */
class Donates
{
    /**
     * @ORM\ManyToOne(targetEntity="Okeanos\CoreBundle\Entity\Users")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Okeanos\CoreBundle\Entity\Actions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $action;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    public function __construct()
    {
        $this->date = new \DateTime;
    }


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
     * Set amount
     *
     * @param integer $amount
     *
     * @return Donates
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Donates
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user.
     *
     * @param \Okeanos\CoreBundle\Entity\Users $user
     *
     * @return Donates
     */
    public function setUser(\Okeanos\CoreBundle\Entity\Users $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \Okeanos\CoreBundle\Entity\Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set action.
     *
     * @param \Okeanos\CoreBundle\Entity\Actions $action
     *
     * @return Donates
     */
    public function setAction(\Okeanos\CoreBundle\Entity\Actions $action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action.
     *
     * @return \Okeanos\CoreBundle\Entity\Actions
     */
    public function getAction()
    {
        return $this->action;
    }
}
