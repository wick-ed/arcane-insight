<?php

/**
 * \Magenerds\ArcaneInsight\Entities\Test
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
 * @ORM\Table(name="test")
 * @JMS\ExclusionPolicy("all")
 */
class Test
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
     * @ORM\Column(type="string", name="keyString")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $key;

    /**
     * The name of the class implementing this test
     *
     * @var string $implementationClass
     *
     * @ORM\Column(type="string")
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $implementationClass;

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
     * Getter for the implementation class
     *
     * @return string
     */
    public function getImplementationClass()
    {
        return $this->implementationClass;
    }

    /**
     * Setter for the implementation class
     *
     * @param string $implementationClass The implementation class to set
     *
     * @return void
     */
    public function setImplementationClass($implementationClass)
    {
        $this->implementationClass = $implementationClass;
    }
}
