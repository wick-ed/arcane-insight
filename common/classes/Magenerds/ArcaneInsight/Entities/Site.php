<?php

/**
 * \Magenerds\ArcaneInsight\Entities\Site
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
 * @ORM\Table(name="site")
 * @JMS\ExclusionPolicy("all")
 */
class Site
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
     * @var string $name
     *
     * @ORM\Column(type="string")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $name;

    /**
     *
     *
     * @var string $url
     *
     * @ORM\Column(type="string")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $url;

    /**
     *
     *
     * @var string $environment
     *
     * @ORM\Column(type="string")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $environment;

    /**
     *
     *
     * @var string $logo
     *
     * @ORM\Column(type="string")
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\Groups({"public", "search"})
     */
    protected $logo;

    /**
     *
     *
     * @var Status $status
     *
     * @ORM\Column(type="string")
     * @ORM\OneToOne(targetEntity="Status", mappedBy="site")
     * @JMS\Expose
     * @JMS\Type("Status")
     * @JMS\Groups({"public", "search"})
     */
    protected $status;

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @param string $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param string $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
}
