<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table(name="task")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TaskRepository")
 */
class Task
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
     * @ORM\Column(name="nameTask", type="string", length=255)
     */
    private $name_task;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_create", type="datetime")
     */
    private $date_create;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime")
     */
    private $date_update;

    /**
     * @ORM\ManyToOne(targetEntity="Status", inversedBy="task")
     * @ORM\JoinColumn(name="status_id", referencedColumnName="id")
     */
    private $status;

    /**
     * Many Task have Many Users.
     * @ORM\ManyToMany(targetEntity="Users", inversedBy="task")
     * @ORM\JoinTable(name="users_tasks")
     */
    private $users;

    /**
     * Task constructor.
     */
    public function __construct() {
        $this->users = new ArrayCollection();
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
     * Set nameTask.
     *
     * @param string $name_task
     *
     * @return Task
     */
    public function setNameTask($name_task)
    {
        $this->name_task = $name_task;

        return $this;
    }

    /**
     * Get nameTask.
     *
     * @return string
     */
    public function getNameTask()
    {
        return $this->name_task;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Task
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreate.
     *
     * @param \DateTime $date_create
     *
     * @return Task
     */
    public function setDateCreate($date_create)
    {
        $this->date_create = $date_create;

        return $this;
    }

    /**
     * Get dateCreate.
     *
     * @return \DateTime
     */
    public function getDateCreate()
    {
        return $this->date_create;
    }

    /**
     * Set dateUpdate.
     *
     * @param \DateTime $date_update
     *
     * @return Task
     */
    public function setDateUpdate($date_update)
    {
        $this->date_update = $date_update;

        return $this;
    }

    /**
     * Get dateUpdate.
     *
     * @return \DateTime
     */
    public function getDateUpdate()
    {
        return $this->date_update;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}
