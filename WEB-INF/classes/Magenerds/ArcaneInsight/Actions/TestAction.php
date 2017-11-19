<?php

/**
 * \Magenerds\ArcaneInsight\Actions\TestAction
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
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Actions;

use AppserverIo\Messaging\ArrayMessage;
use AppserverIo\Messaging\MessageQueue;
use AppserverIo\Messaging\QueueConnectionFactory;
use AppserverIo\RemoteMethodInvocation\RemoteProxy;
use AppserverIo\Routlt\ActionInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;
use Magenerds\ArcaneInsight\Services\TestProcessor;
use Magenerds\ArcaneInsight\Util\MessageKeys;

/**
 * Default action implementation.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @Path(name="/tests")
 *
 * @Results({
 *     @Result(name="success", result="success_view_data", type="AppserverIo\Routlt\Results\RawResult"),
 *     @Result(name="failure", result="failure_view_data", type="AppserverIo\Routlt\Results\RawResult")
 * })
 */
class TestAction extends AbstractRestfulAction
{

    /**
     * The entity type this bean is primarily targeting
     *
     * @var string TARGET_ENTITY
     */
    const TARGET_ENTITY = 'Magenerds\ArcaneInsight\Entities\Test';

    /**
     * Given test
     *
     * @var string
     */
    protected $test;

    /**
     * Given site
     *
     * @var string
     */
    protected $site;

    /**
     * The test processor instance
     *
     * @var TestProcessor|RemoteProxy
     * @EnterpriseBean
     */
    protected $testProcessor;

    /**
     * @return TestProcessor|RemoteProxy
     */
    protected function getTestProcessor()
    {
        return $this->testProcessor;
    }

    /**
     * @return string
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * @param string $test
     */
    public function setTest($test)
    {
        $this->test = $test;
    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param string $site
     */
    public function setSite($site)
    {
        $this->site = $site;
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
            $tests = $this->getTestProcessor()->read($this->getPrimaryId());

        } catch (\Exception $e) {
            // provide an error message and status
            $this->addFieldError('critical', $this->filterResponseMessage($e->getMessage()));
            $servletResponse->setStatusCode(500);
            // tell them we failed
            return ActionInterface::FAILURE;
        }

        // check if we did find something
        if (!is_null($this->getPrimaryId()) && empty($tests)) {
            // provide an error message and status
            $servletResponse->setStatusCode(404);
            // tell them we failed
            return ActionInterface::FAILURE;
        }
        // propagate to our attribute map
        $this->setAttribute('success_view_data', $tests);
        //tell them we succeeded
        return ActionInterface::SUCCESS;
    }
}
