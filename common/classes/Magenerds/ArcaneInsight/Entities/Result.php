<?php

/**
 * \Magenerds\ArcaneInsight\Entities\Result
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Doctrine entity that represents a profile.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @ORM\Entity
 * @ORM\Table(name="result")
 * @JMS\ExclusionPolicy("all")
 */
class Result
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
     * The unique key identifying the result by type
     *
     * @var string $key
     *
     * @ORM\Column(type="string")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $key;

    /**
     * The grade of severity this result has
     *
     * @var integer $severity
     *
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\Groups({"public", "search"})
     */
    protected $severity;

    /**
     * The actual result staus.
     * -1 = failed, 0 = unknown, 1 = success
     *
     * @var integer $status
     *
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\Groups({"public", "search"})
     */
    protected $statusCode = 0;

    /**
     * The report being the base of this result
     *
     * @var Report $report
     *
     * @ORM\ManyToOne(targetEntity="Report", cascade={"persist"})
     * @JMS\Type("Magenerds\ArcaneInsight\Entities\Report")
     * @JMS\Groups({"public", "search"})
     */
    protected $report;

    /**
     * Timestamp identifying the time this result was generated
     *
     * @var integer $timestamp
     *
     * @ORM\Column(type="integer")
     * @JMS\Type("integer")
     * @JMS\Groups({"public", "search"})
     */
    protected $timestamp;

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
     * Getter for the result key
     *
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Setter for the result key
     *
     * @param string $key The result key to set
     *
     * @return void
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * Getter for the result severity
     *
     * @return integer
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * Setter for the result severity
     *
     * @param integer $severity The severity to set
     *
     * @return void
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
    }

    /**
     * Getter for the actual result status. -1 = failed, 0 = unknown, 1 = success
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for the result status
     *
     * @param integer $statusCode The status to set. -1 = failed, 0 = unknown, 1 = success
     *
     * @return void
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Getter for the report which caused the result
     *
     * @return Report
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * Setter for the result report
     *
     * @param Report $report The report to set
     *
     * @return void
     */
    public function setReport(Report $report)
    {
        $this->report = $report;
    }

    /**
     * Getter for the result timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Setter for the result timestamp
     *
     * @param int $timestamp The timestamp to set
     *
     * @return void
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
