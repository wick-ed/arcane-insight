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
use AppserverIo\Routlt\ActionInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;
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
     * The site processor instance
     *
     * @var SiteProcessor|RemoteProxy
     * @EnterpriseBean
     */
    protected $siteProcessor;

    /**
     * @return SiteProcessor|RemoteProxy
     */
    protected function getResultProcessor()
    {
        return $this->siteProcessor;
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
