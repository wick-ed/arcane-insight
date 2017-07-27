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
     *
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
     *
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
     *
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
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return Result[]
     */
    public function getResults()
    {
        return $this->results;
    }

    /**
     * @param Result[] $results
     */
    public function setResults($results)
    {
        $this->results = $results;
    }
}
