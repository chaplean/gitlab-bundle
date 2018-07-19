<?php

namespace Chaplean\Bundle\GitlabBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Chaplean\Bundle\GitlabBundle\Repository\CoverageRepository")
 * @ORM\Table(
 *     name="cl_gl_coverage",
 *     indexes={@ORM\Index(name="coverage_sha_INDEX", columns={"sha"})}
 * )
 */
class Coverage
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
     * @var float
     *
     * @ORM\Column(type="decimal", length=5, nullable=false, name="percent_covered", precision=5, scale=4)
     */
    private $percentCovered;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false, name="sha")
     */
    private $sha;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=false, name="date_add")
     * @Gedmo\Timestampable(on="create")
     */
    private $dateAdd;

    /**
     * @var Branch
     *
     * @ORM\ManyToOne(targetEntity="Chaplean\Bundle\GitlabBundle\Entity\Branch", inversedBy="coverages")
     * @ORM\JoinColumn(name="branch_id", referencedColumnName="id", nullable=false, onDelete="RESTRICT")
     */
    private $branch;

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
     * Set percentCovered.
     *
     * @param string $percentCovered
     *
     * @return Coverage
     */
    public function setPercentCovered($percentCovered)
    {
        $this->percentCovered = $percentCovered;

        return $this;
    }

    /**
     * Get percentCovered.
     *
     * @return string
     */
    public function getPercentCovered()
    {
        return $this->percentCovered;
    }

    /**
     * Set sha.
     *
     * @param string $sha
     *
     * @return Coverage
     */
    public function setSha($sha)
    {
        $this->sha = $sha;

        return $this;
    }

    /**
     * Get sha.
     *
     * @return string
     */
    public function getSha()
    {
        return $this->sha;
    }

    /**
     * Set dateAdd.
     *
     * @param \DateTime $dateAdd
     *
     * @return Coverage
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
     * Set branch.
     *
     * @param Branch $branch
     *
     * @return Coverage
     */
    public function setBranch(Branch $branch)
    {
        $this->branch = $branch;

        return $this;
    }

    /**
     * Get branch.
     *
     * @return Branch
     */
    public function getBranch()
    {
        return $this->branch;
    }
}
