<?php

/**
 * \Magenerds\ArcaneInsight\Entities\Report
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
 * @ORM\Table(name="report")
 * @JMS\ExclusionPolicy("all")
 */
class Report
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
     * The complete report log
     *
     * @var string $log
     *
     * @ORM\Column(type="text")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $log;

    /**
     * Timestamp tracking the latest change to the result entry
     *
     * @var integer $timestamp
     *
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
     * Getter for the report log
     *
     * @return string
     */
    public function getLog()
    {
        return $this->log;
    }

    /**
     * Setter for the report log
     *
     * @param string $log The log to set
     *
     * @return void
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * Getter for the report timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Setter for the report timestamp
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
