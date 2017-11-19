<?php

/**
 * \Magenerds\ArcaneInsight\Entities\Ward
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Entities;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 *
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @ORM\Entity
 * @ORM\Table(name="ward")
 * @JMS\ExclusionPolicy("all")
 */
class Ward
{

    /**
     * The unique identifier.
     *
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $id;

    /**
     * The cron used for frequency
     *
     * @var string $cron
     *
     * @ORM\Column(type="string")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $cron;

    /**
     * The site the status is for
     *
     * @var Site $site
     *
     * @ORM\ManyToOne(targetEntity="Site", cascade={"persist"})
     * @JMS\Expose
     * @JMS\Type("Magenerds\ArcaneInsight\Entities\Site")
     * @JMS\Groups({"public", "search"})
     */
    protected $site;

    /**
     * The current status of the site. Based on latest test results
     *
     * @var Status $status
     *
     * @ORM\OneToOne(targetEntity="Status", mappedBy="ward", cascade={"persist"})
     * @JMS\Expose
     * @JMS\Type("Magenerds\ArcaneInsight\Entities\Status")
     * @JMS\Groups({"public", "search"})
     */
    protected $status;

    /**
     * The list of tests this ward uses
     *
     * @var Test[] $tests
     *
     * @ORM\ManyToMany(targetEntity="Test", cascade={"persist"})
     * @ORM\JoinTable(name="wards_tests",
     *      joinColumns={@ORM\JoinColumn(name="ward_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="test_id", referencedColumnName="id")}
     *      )
     * @JMS\Expose
     * @JMS\Type("array")
     * @JMS\Groups({"public", "search"})
     */
    protected $tests;

    /**
     * Ward constructor
     */
    public function __construct()
    {
        $this->status = new Status();
        $this->status->setCode(0);
        $this->tests = new ArrayCollection();
    }

    /**
     * Getter for the ID
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Setter for the ID
     *
     * @param string $id The ID to set
     *
     * @return void
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Getter for the cron string
     *
     * @return string
     */
    public function getCron()
    {
        return $this->cron;
    }

    /**
     * Setter for the cron string
     *
     * @param string $cron The cron string to set
     *
     * @return void
     */
    public function setCron($cron)
    {
        $this->cron = $cron;
    }

    /**
     * The getter for the site
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * The setter for the site
     *
     * @param Site $site The site to set
     *
     * @return void
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * The getter for the status
     *
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * The setter for the status
     *
     * @param Status $status The site to set
     *
     * @return void
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * Getter for the tests
     *
     * @return Test[]
     */
    public function getTests()
    {
        return $this->tests;
    }

    /**
     * The setter for the tests
     *
     * @param Test[] $tests The tests collection to set
     *
     * @return void
     */
    public function setTests($tests)
    {
        $this->tests = $tests;
    }


    /**
     * Add a test to the ward
     *
     * @param Test $test The test to add
     *
     * @return void
     */
    public function addTest($test)
    {
        $this->tests->add($test);
    }
}
