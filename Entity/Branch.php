<?php

namespace Chaplean\Bundle\GitlabBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="cl_gl_branch",
 *     uniqueConstraints={@ORM\UniqueConstraint(
 *             name="project_branch_name_UNIQUE",
 *             columns={"project_id","name"}
 *         )}
 * )
 */
class Branch
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
     * @var Project
     *
     * @ORM\ManyToOne(targetEntity="Chaplean\Bundle\GitlabBundle\Entity\Project", inversedBy="branches")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    private $project;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Chaplean\Bundle\GitlabBundle\Entity\Coverage", mappedBy="branch")
     */
    private $coverages;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->coverages = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return Branch
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
     * @return Branch
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
     * Set project.
     *
     * @param Project $project
     *
     * @return Branch
     */
    public function setProject(Project $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project.
     *
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * Add coverage.
     *
     * @param Coverage $coverage
     *
     * @return Branch
     */
    public function addCoverage(Coverage $coverage)
    {
        $this->coverages[] = $coverage;

        return $this;
    }

    /**
     * Remove coverage.
     *
     * @param Coverage $coverage
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeCoverage(Coverage $coverage)
    {
        return $this->coverages->removeElement($coverage);
    }

    /**
     * Get coverages.
     *
     * @return ArrayCollection
     */
    public function getCoverages()
    {
        return $this->coverages;
    }
}
