<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Status
 *
 * @ORM\Table(name="status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatusRepository")
 */
class Status
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
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="descript_status", type="text")
     */
    private $descript_status;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="status")
     */
    private $task;

    public function __construct()
    {
        $this->task = new ArrayCollection();
    }
    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Status
     */
    public function setTitle($title)
    {
        $this->title = $title;


        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set descriptStatus.
     *
     * @param string $descript_status
     *
     * @return Status
     */
    public function setDescriptStatus($descript_status)
    {
        $this->descriptStatus = $descript_status;

        return $this;
    }

    /**
     * Get descriptStatus.
     *
     * @return string
     */
    public function getDescriptStatus()
    {
        return $this->descript_status;
    }
}
