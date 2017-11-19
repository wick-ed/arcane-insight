<?php

/**
 * \Magenerds\ArcaneInsight\Actions\WardAction
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
use Magenerds\ArcaneInsight\Entities\Test;
use Magenerds\ArcaneInsight\Entities\Ward;
use Magenerds\ArcaneInsight\Services\TestProcessor;
use Magenerds\ArcaneInsight\Services\WardProcessor;
use Magenerds\ArcaneInsight\Util\MessageKeys;

/**
 * Default action implementation.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @Path(name="/wards")
 *
 * @Results({
 *     @Result(name="success", result="success_view_data", type="AppserverIo\Routlt\Results\RawResult"),
 *     @Result(name="failure", result="failure_view_data", type="AppserverIo\Routlt\Results\RawResult")
 * })
 */
class WardAction extends AbstractRestfulAction
{

    /**
     * The entity type this bean is primarily targeting
     *
     * @var string TARGET_ENTITY
     */
    const TARGET_ENTITY = 'Magenerds\ArcaneInsight\Entities\Ward';

    /**
     * A single ward instance passed to us
     *
     * @var Ward|null $ward
     */
    protected $ward = null;

    /**
     * A single test instance passed to us
     *
     * @var Test|null $test
     */
    protected $test = null;

    /**
     * Several wards passed to us
     *
     * @var Ward[] $wards
     */
    protected $wards = array();

    /**
     * The ward processor instance
     *
     * @var WardProcessor|RemoteProxy
     * @EnterpriseBean
     */
    protected $wardProcessor;

    /**
     * @return WardProcessor|RemoteProxy
     */
    protected function getWardProcessor()
    {
        return $this->wardProcessor;
    }

    /**
     * Sets a given ward instance
     *
     * @param object $ward The ward
     *
     * @return void
     */
    public function setWard($ward)
    {
        // for a single instance we have to extract the ID from the path info
        if (!is_null($this->getPrimaryId()) && !isset($ward->id)) {
            $ward->id = (string) $this->getPrimaryId();
        }
        // now convert to entity
        $this->ward = $this->convertToEntity($ward);
    }

    /**
     * Sets a given test instance
     *
     * @param object $test The test
     *
     * @return void
     */
    public function setTest($test)
    {
        // for a single instance we have to extract the ID from the path info
        if (!is_null($this->getSecondaryId()) && !isset($test->id)) {
            $test->id = (string) $this->getSecondaryId();
        }
        // now convert to entity
        $this->test = $this->convertToEntity($test, Test::class);
    }

    /**
     * Set several ward instances at once
     *
     * @param array $wards The ward instances to set
     *
     * @return void
     */
    public function setWards(array $wards)
    {
        foreach ($wards as $ward) {
            $this->wards[] = $this->convertToEntity($ward);
        }
    }

    /**
     * Gets a given ward instance
     *
     * @return Ward
     */
    public function getWard()
    {
        return $this->ward;
    }

    /**
     * Gets a given test instance
     *
     * @return Test
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Get several ward instances at once
     *
     * @return Ward[]
     */
    public function getWards()
    {
        $wards = $this->wards;
        if (!is_null($this->ward)) {
            $wards = array_merge(array($this->ward), $wards);
        }
        return $wards;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return self::TARGET_ENTITY;
    }

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/update")
     */
    public function updateStatusAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        try {
            if (empty($this->getPrimaryId())) {
                throw new \Exception('missing vital information');
            }

            $this->getWardProcessor()->updateStatus($this->getPrimaryId());
            $this->setAttribute('success_view_data', array('message' => 'ok'));
            return ActionInterface::SUCCESS;

        } catch (\Exception $e) {
            // append the exception the response body
            $this->addFieldError('critical', $this->filterResponseMessage($e->getMessage()));

            // action invocation has been successfull
            return ActionInterface::FAILURE;
        }
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
        try {
            // create actions do not accept a passed id
            if (!is_null($this->getPrimaryId())) {
                // we might also have to edit a sub-entity
                if (!is_null($this->getSecondaryEntity()) && is_null($this->getSecondaryId())) {
                    $this->getWardProcessor()->addTest($this->getPrimaryId(), $this->getTest());
                } else {
                    // provide an error message and status
                    $this->addFieldError('critical', '"create" does not accept an ID');
                    $servletResponse->setStatusCode(405);

                    // tell them we failed
                    return ActionInterface::FAILURE;
                }
            } else {
                // create the user within the persistence layer
                /** @var Ward $ward */
                $this->getWardProcessor()->create($this->getWard());
            }
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
            $wards = $this->getWardProcessor()->read($this->getPrimaryId());

        } catch (\Exception $e) {
            // provide an error message and status
            $this->addFieldError('critical', $this->filterResponseMessage($e->getMessage()));
            $servletResponse->setStatusCode(500);
            // tell them we failed
            return ActionInterface::FAILURE;
        }

        // check if we did find something
        if (!is_null($this->getPrimaryId()) && empty($wards)) {
            // provide an error message and status
            $servletResponse->setStatusCode(404);
            // tell them we failed
            return ActionInterface::FAILURE;
        }
        // propagate to our attribute map
        $this->setAttribute('success_view_data', $wards);
        //tell them we succeeded
        return ActionInterface::SUCCESS;
    }
}
