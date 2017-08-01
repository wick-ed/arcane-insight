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
     * The site's name
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
     * The base URL the site is reachable under
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
     * The environment the site is under.
     * E.g. production, staging, test, ...
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
     * The logo the site will have in any dashboard overview
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
     * The current status of the site. Based on latest check results
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
     * Getter for the site's name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Setter for the site's name
     *
     * @param string $name The site name to set
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Getter for the site URL
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Setter for the site URL
     *
     * @param string $url The site URL to set
     *
     * @return void
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Getter for the site environment
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * Getter for the site environment
     *
     * @param string $environment The environment to set
     *
     * @return void
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }

    /**
     * Getter for the site logo
     *
     * @return string
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Setter for the site logo
     *
     * @param string $logo The site logo to set
     *
     * @return void
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }
}
