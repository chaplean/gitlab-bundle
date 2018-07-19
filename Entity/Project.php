<?php

namespace Chaplean\Bundle\GitlabBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Chaplean\Bundle\GitlabBundle\Repository\ProjectRepository")
 * @ORM\Table(name="cl_gl_project")
 */
class Project
{
    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(type="integer", options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", unique=true, nullable=false, name="gitlab_id", options={"unsigned":true})
     */
    private $gitlabId;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, name="name")
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false, name="date_add")
     * @Gedmo\Timestampable(on="create")
     */
    private $dateAdd;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chaplean\Bundle\GitlabBundle\Entity\Branch", mappedBy="project")
     */
    private $branches;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->branches = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set gitlabId.
     *
     * @param integer $gitlabId
     *
     * @return Project
     */
    public function setGitlabId($gitlabId)
    {
        $this->gitlabId = $gitlabId;

        return $this;
    }

    /**
     * Get gitlabId.
     *
     * @return integer
     */
    public function getGitlabId()
    {
        return $this->gitlabId;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Project
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set dateAdd.
     *
     * @param \DateTime $dateAdd
     *
     * @return Project
     */
    public function setDateAdd($dateAdd)
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    /**
     * Get dateAdd.
     *
     * @return \DateTime
     */
    public function getDateAdd()
    {
        return $this->dateAdd;
    }

    /**
     * Add branch.
     *
     * @param Branch $branch
     *
     * @return Project
     */
    public function addBranch(Branch $branch)
    {
        $this->branches[] = $branch;

        return $this;
    }

    /**
     * Remove branch.
     *
     * @param Branch $branch
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeBranch(Branch $branch)
    {
        return $this->branches->removeElement($branch);
    }

    /**
     * Get branches.
     *
     * @return Collection
     */
    public function getBranches()
    {
        return $this->branches;
    }
}
