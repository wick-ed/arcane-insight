<?php

/**
 * \Magenerds\ArcaneInsight\Actions\CheckAction
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
 * @Path(name="/check")
 *
 * @Results({
 *     @Result(name="success", result="success_view_data", type="AppserverIo\Routlt\Results\RawResult"),
 *     @Result(name="failure", result="failure_view_data", type="AppserverIo\Routlt\Results\RawResult")
 * })
 */
class CheckAction extends AbstractDispatchAction
{

    /**
     * Given check
     *
     * @var string
     */
    protected $check;

    /**
     * Given site
     *
     * @var string
     */
    protected $site;

    /**
     * @return string
     */
    public function getCheck()
    {
        return $this->check;
    }

    /**
     * @param string $check
     */
    public function setCheck($check)
    {
        $this->check = $check;
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
     * Default action to invoke if no action parameter has been found in the request.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     *
     * @Action(name="/check")
     */
    public function checkAction(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        try {
            if (empty($this->getSite()) || empty($this->getCheck())) {
                throw new \Exception('missing vital information');
            }

            // load the application name
            $applicationName = $this->getApplication()->getName();

            // initialize the connection and the session
            $queue = MessageQueue::createQueue('pms/check-dispatcher');
            $connection = QueueConnectionFactory::createQueueConnection($applicationName);
            $session = $connection->createQueueSession();
            $sender = $session->createSender($queue);

            // initialize the message with the directory containing the dump files
            $message = new ArrayMessage(array(
                MessageKeys::SITE => $this->getSite(),
                MessageKeys::CHECK => $this->getCheck()
            ));

            // create a new message and send it
            $sender->send($message, false);

            $this->setAttribute('success_view_data', array('message' => 'ok'));
            return ActionInterface::SUCCESS;

        } catch (\Exception $e) {
            // append the exception the response body
            $this->addFieldError('critical', $this->filterResponseMessage($e->getMessage()));

            // action invocation has been successfull
            return ActionInterface::FAILURE;
        }
    }
}
