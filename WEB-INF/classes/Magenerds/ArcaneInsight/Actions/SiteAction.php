<?php

/**
 * \Magenerds\ArcaneInsight\Actions\SiteAction
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Actions;

use AppserverIo\Messaging\ArrayMessage;
use AppserverIo\Messaging\MessageQueue;
use AppserverIo\Messaging\QueueConnectionFactory;
use AppserverIo\RemoteMethodInvocation\RemoteProxy;
use AppserverIo\Routlt\ActionInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;
use Magenerds\ArcaneInsight\Entities\Site;
use Magenerds\ArcaneInsight\Services\SiteProcessor;
use Magenerds\ArcaneInsight\Util\MessageKeys;

/**
 * Default action implementation.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 *
 * @Path(name="/sites")
 *
 * @Results({
 *     @Result(name="success", result="success_view_data", type="AppserverIo\Routlt\Results\RawResult"),
 *     @Result(name="failure", result="failure_view_data", type="AppserverIo\Routlt\Results\RawResult")
 * })
 */
class SiteAction extends AbstractRestfulAction
{

    /**
     * The entity type this bean is primarily targeting
     *
     * @var string TARGET_ENTITY
     */
    const TARGET_ENTITY = 'Magenerds\ArcaneInsight\Entities\Site';

    /**
     * A single site instance passed to us
     *
     * @var Site|null $site
     */
    protected $site = null;

    /**
     * Several sites passed to us
     *
     * @var Site[] $sites
     */
    protected $sites = array();

    /**
     * The site processor instance
     *
     * @var SiteProcessor|RemoteProxy
     * @EnterpriseBean
     */
    protected $siteProcessor;

    /**
     * @return SiteProcessor|RemoteProxy
     */
    protected function getSiteProcessor()
    {
        return $this->siteProcessor;
    }

    /**
     * Sets a given site instance
     *
     * @param object $site The site
     *
     * @return void
     */
    public function setSite($site)
    {
        // for a single instance we have to extract the ID from the path info
        if (is_numeric($this->getPrimaryId()) && !isset($site->id)) {
            $site->id = (int) $this->getPrimaryId();
        }
        // now convert to user entity
        $this->site = $this->convertToEntity($site);
    }

    /**
     * Set several site instances at once
     *
     * @param array $sites The site instances to set
     *
     * @return void
     */
    public function setSites(array $sites)
    {
        foreach ($sites as $site) {
            $this->sites[] = $this->convertToEntity($site);
        }
    }

    /**
     * Gets a given site instance
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Get several site instances at once
     *
     * @return Site[]
     */
    public function getSites()
    {
        $sites = $this->sites;
        if (!is_null($this->site)) {
            $sites = array_merge(array($this->site), $sites);
        }
        return $sites;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return self::TARGET_ENTITY;
    }

    /**
     * Index action used to dispatch requests based on their HTTP method
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null
     *
     * @Action(name="")
     * @Post
     */
    public function createAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        // create actions do not accept a passed id
        if (!is_null($this->getPrimaryId())) {
            // provide an error message and status
            $this->addFieldError('critical', '"create" does not accept an ID');
            $servletResponse->setStatusCode(405);
            // tell them we failed
            return ActionInterface::FAILURE;
        }

        try {
            // create the user within the persistence layer
            /** @var Site $site */
            $site = $this->getSiteProcessor()->create($this->getSite());
            $this->setAttribute('success_view_data', $site->getId());

        } catch (\Exception $e) {
            // provide an error message and status
            $this->addFieldError('critical', $this->filterResponseMessage($e->getMessage()));
            $servletResponse->setStatusCode(500);
            // tell them we failed
            return ActionInterface::FAILURE;
        }
        //tell them we succeeded
        $servletResponse->setStatusCode(201);
        return ActionInterface::SUCCESS;
    }

    /**
     * Index action used to dispatch requests based on their HTTP method
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null
     *
     * @Action(name="")
     * @Get
     */
    public function readAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        try {
            // load user(s) from the persistence layer
            $sites = $this->getSiteProcessor()->read($this->getPrimaryId());

        } catch (\Exception $e) {
            // provide an error message and status
            $this->addFieldError('critical', $this->filterResponseMessage($e->getMessage()));
            $servletResponse->setStatusCode(500);
            // tell them we failed
            return ActionInterface::FAILURE;
        }

        // check if we did find something
        if (!is_null($this->getPrimaryId()) && empty($sites)) {
            // provide an error message and status
            $servletResponse->setStatusCode(404);
            // tell them we failed
            return ActionInterface::FAILURE;
        }
        // propagate to our attribute map
        $this->setAttribute('success_view_data', $sites);
        //tell them we succeeded
        return ActionInterface::SUCCESS;
    }

    /**
     * Index action used to dispatch requests based on their HTTP method
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null
     *
     * @Action(name="/status")
     * @Get
     */
    public function statusAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        try {
            // load user(s) from the persistence layer
            $status = $this->getSiteProcessor()->getStatus($this->getPrimaryId());

        } catch (\Exception $e) {
            // provide an error message and status
            $this->addFieldError('critical', $this->filterResponseMessage($e->getMessage()));
            $servletResponse->setStatusCode(500);
            // tell them we failed
            return ActionInterface::FAILURE;
        }

        // check if we did find something
        if (!is_null($this->getPrimaryId()) && empty($status)) {
            // provide an error message and status
            $servletResponse->setStatusCode(404);
            // tell them we failed
            return ActionInterface::FAILURE;
        }
        // propagate to our attribute map
        $this->setAttribute('success_view_data', $status);
        //tell them we succeeded
        return ActionInterface::SUCCESS;
    }
}
