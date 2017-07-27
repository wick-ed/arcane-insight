<?php

/**
 * \Magenerds\ArcaneInsight\MessageBeans\ImportReceiver
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\MessageBeans;

use AppserverIo\Psr\Pms\MessageInterface;
use AppserverIo\Messaging\AbstractMessageListener;
use AppserverIo\RemoteMethodInvocation\RemoteProxy;
use Magenerds\ArcaneInsight\Checks\LocationReachability;
use Magenerds\ArcaneInsight\Entities\Site;
use Magenerds\ArcaneInsight\Services\ResultProcessor;
use Magenerds\ArcaneInsight\Util\MessageKeys;

/**
 * This is the implementation of a import message receiver.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 *
 * @MessageDriven
 */
class CheckReceiver extends AbstractMessageListener
{

    /**
     * The result processor instance
     *
     * @var ResultProcessor|RemoteProxy
     * @EnterpriseBean
     */
    protected $resultProcessor;

    /**
     * @return ResultProcessor|RemoteProxy
     */
    protected function getResultProcessor()
    {
        return $this->resultProcessor;
    }

    /**
     * Will be invoked when a new message for this message bean will be available.
     *
     * @param \AppserverIo\Psr\Pms\MessageInterface $message   A message this message bean is listen for
     * @param string                                $sessionId The session ID
     *
     * @return void
     * @see \AppserverIo\Psr\Pms\MessageListenerInterface::onMessage()
     */
    public function onMessage(MessageInterface $message, $sessionId)
    {
        $messageContent = $message->getMessage();

        $site = new Site();
        $site->setUrl($messageContent[MessageKeys::SITE]);

        // start the import/export process
        $this->execute($site);

        // update the message monitory
        $this->updateMonitor($message);
    }

    /**
     *
     *
     * @return void
     */
    public function execute($site)
    {
        $check = new LocationReachability();
        $result = $check->execute($site);

        $this->getResultProcessor()->create($result);
    }
}
