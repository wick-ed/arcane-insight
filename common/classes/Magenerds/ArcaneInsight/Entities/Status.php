<?php

/**
 * \Magenerds\ArcaneInsight\Entities\Status
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Entities;

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
     * The ward the status is for
     *
     * @var Ward $ward
     *
     * @ORM\OneToOne(targetEntity="Ward", inversedBy="status")
     * @JMS\Expose
     * @JMS\Type("Magenerds\ArcaneInsight\Entities\Ward")
     * @JMS\Groups({"public", "search"})
     */
    protected $ward;

    /**
     * The results which hold the test data for this status
     *
     * @var Result[] $results
     *
     *
     * @ORM\OneToMany(targetEntity="Result", mappedBy="status")
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
     * The getter for the ward
     *
     * @return Ward
     */
    public function getWard()
    {
        return $this->ward;
    }

    /**
     * The setter for the ward
     *
     * @param Ward $ward The ward to set
     *
     * @return void
     */
    public function setWard($ward)
    {
        $this->ward = $ward;
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
