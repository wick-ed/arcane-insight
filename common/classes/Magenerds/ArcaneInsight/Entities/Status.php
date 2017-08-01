<?php

/**
 * \Magenerds\ArcaneInsight\Entities\Status
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 *
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 *
 * @ORM\Entity
 * @ORM\Table(name="status")
 * @JMS\ExclusionPolicy("all")
 */
class Status
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
     * The status code
     *
     * @var integer $code
     *
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\Groups({"public", "search"})
     */
    protected $code;

    /**
     * The site the status is for
     *
     * @var Site $site
     *
     * @ORM\OneToOne(targetEntity="Site", mappedBy="status")
     * @JMS\Expose
     * @JMS\Type("Site")
     * @JMS\Groups({"public", "search"})
     */
    protected $site;

    /**
     * The results which hold the check data for this status
     *
     * @var Result[] $results
     *
     *
     * @ORM\ManyToMany(targetEntity="Result")
     * @ORM\JoinTable(name="status_results",
     *      joinColumns={@ORM\JoinColumn(name="status_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="result_id", referencedColumnName="id", unique=true)}
     *      )
     *
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $results;

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
     * Getter for the code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Setter for the code
     *
     * @param int $code The code to set
     *
     * @return void
     */
    public function setCode($code)
    {
        $this->code = $code;
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
     * Getter for the results
     *
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * The setter for the results
     *
     * @param Result[] $results The result collection to set
     *
     * @return void
     */
    public function setResults($results)
    {
        $this->results = $results;
    }
}
