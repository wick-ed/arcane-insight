<?php

/**
 * \Magenerds\ArcaneInsight\Entities\Result
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Entities;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;

/**
 * Doctrine entity that represents a profile.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
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
     *
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
     *
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
     *
     *
     * @var integer $status
     *
     * @ORM\Column(type="integer")
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\Groups({"public", "search"})
     */
    protected $status;

    /**
     * The image relations.
     *
     * @var Report $report
     *
     * @ORM\ManyToOne(targetEntity="Report", cascade={"persist"})
     * @JMS\Type("\Magenerds\ArcaneInsight\Entities\Report")
     * @JMS\Groups({"public", "search"})
     */
    protected $report;

    /**
     * Timestamp tracking the latest change to the result entry
     *
     * @var integer $timestamp
     *
     * @ORM\Column(type="integer")
     * @JMS\Type("integer")
     * @JMS\Groups({"public", "search"})
     */
    protected $timestamp;

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
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * @return integer
     */
    public function getSeverity()
    {
        return $this->severity;
    }

    /**
     * @param integer $severity
     */
    public function setSeverity($severity)
    {
        $this->severity = $severity;
    }

    /**
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param integer $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return Report
     */
    public function getReport()
    {
        return $this->report;
    }

    /**
     * @param Report $report
     */
    public function setReport(Report $report)
    {
        $this->report = $report;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
