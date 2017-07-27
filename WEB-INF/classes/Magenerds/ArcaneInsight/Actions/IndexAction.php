<?php

/**
 * \Magenerds\ArcaneInsight\Actions\IndexAction
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
 * @Path(name="/index")
 *
 * @Results({
 *     @Result(name="input", result="/index.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 */
class IndexAction extends AbstractDispatchAction
{

    /**
     * Default action to invoke if no action parameter has been found in the request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/index")
     */
    public function indexAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        try {
            return ActionInterface::INPUT;

        } catch (\Exception $e) {
            // append the exception the response body
            $this->addFieldError('critical', $this->filterResponseMessage($e->getMessage()));

            // action invocation has been successfull
            return ActionInterface::INPUT;
        }
    }
}
