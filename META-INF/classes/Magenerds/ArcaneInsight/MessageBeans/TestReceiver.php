<?php

/**
 * \Magenerds\ArcaneInsight\MessageBeans\ImportReceiver
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\MessageBeans;

use AppserverIo\Psr\Pms\MessageInterface;
use AppserverIo\Messaging\AbstractMessageListener;
use AppserverIo\RemoteMethodInvocation\RemoteProxy;
use Magenerds\ArcaneInsight\Entities\Status;
use Magenerds\ArcaneInsight\Tests\TestInterface;
use Magenerds\ArcaneInsight\Entities\Ward;
use Magenerds\ArcaneInsight\Services\WardProcessor;
use Magenerds\ArcaneInsight\Util\MessageKeys;

/**
 * This is the implementation of a import message receiver.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @MessageDriven
 */
class TestReceiver extends AbstractMessageListener
{

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

        $ward = $this->getWardProcessor()->read($messageContent[MessageKeys::WARD_ID]);
        $this->execute($ward);

        // update the message monitory
        $this->updateMonitor($message);
    }

    /**
     *
     *
     * @return void
     */
    public function execute(Ward $ward)
    {
        // iterate all tests and update the status results
        $site = $ward->getSite();
        $status = $ward->getStatus();
        if (is_null($status)) {
            $status = new Status();
        }
        $results = [];
        /** @var TestInterface $test */
        foreach ($ward->getTests() as $test) {
            $results[] = $test->execute($site);
        }
        // update the ward with the new status
        $status->setResults($results);
        $this->getWardProcessor()->update([$ward]);
    }
}
