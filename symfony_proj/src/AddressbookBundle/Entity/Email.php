<?php

namespace AddressbookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity(repositoryClass="AddressbookBundle\Repository\EmailRepository")
 */
class Email
{
    /**
    * @ORM\ManyToOne(targetEntity="Person", inversedBy="emails")
    * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
    */

    private $person_id;

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
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=50)
     */
    private $type;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Email
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
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
     * Set type
     *
     * @param string $type
     * @return Email
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set person_id
     *
     * @param \AddressbookBundle\Entity\Person $personId
     * @return Email
     */
    public function setPersonId(\AddressbookBundle\Entity\Person $personId = null)
    {
        $this->person_id = $personId;

        return $this;
    }

    /**
     * Get person_id
     *
     * @return \AddressbookBundle\Entity\Person 
     */
    public function getPersonId()
    {
        return $this->person_id;
    }
}
