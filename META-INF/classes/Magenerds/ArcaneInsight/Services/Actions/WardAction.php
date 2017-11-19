<?php

/**
 * \Magenerds\ArcaneInsight\Services\Actions\WardAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services\Actions;

use AppserverIo\Messaging\ArrayMessage;
use AppserverIo\Messaging\MessageQueue;
use AppserverIo\Messaging\QueueConnectionFactory;
use AppserverIo\Psr\Application\ApplicationInterface;
use Magenerds\ArcaneInsight\Entities\Test;
use Magenerds\ArcaneInsight\Entities\Ward;
use Magenerds\ArcaneInsight\Util\MessageKeys;

/**
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @Stateless
 */
class WardAction extends AbstractDatabaseQueryAction
{

    //<editor-fold desc="Class constants">
    /**
     * The entity type this bean is primarily targeting
     *
     * @var string TARGET_ENTITY
     */
    const TARGET_ENTITY = 'Magenerds\ArcaneInsight\Entities\Ward';
    //</editor-fold>

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @Resource(name="ApplicationInterface")
     */
    protected $application;

    /**
     * Returns the application
     *
     * @return ApplicationInterface
     */
    public function getApplication()
    {
        return $this->application;
    }

    //<editor-fold desc="Public getters">
    /**
     * Returns the class name of the entity this bean primarily deals with
     *
     * @return string
     */
    public function getTargetEntity()
    {
        return static::TARGET_ENTITY;
    }
    //</editor-fold>

    /**
     * Persists a collection of wards
     *
     * @param Ward $ward          Ward entity to persist
     *
     * @return Ward
     */
    public function create(Ward $ward)
    {
        // persist the data
        $entityManager = $this->getEntityManager();
        /** @var Ward $ward */
        $ward = $entityManager->merge($ward);
        $entityManager->flush();

        // return the new ward
        return $ward;
    }

    /**
     * Loads all or only a certain ward entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return Ward[]|Ward
     */
    public function read($id = null)
    {
        if (is_null($id)) {
            $wards = $this->findAll();
        } else {
            $wards = $this->findById($id);
        }

        // sanitize output
        if (is_null($wards)) {
            $wards = array();
        }

        // return what we got
        return $wards;
    }

    /**
     *
     *
     * @param Ward[] $wards
     *
     * @return void
     */
    public function update(array $wards)
    {
        // check for IDs
        foreach ($wards as $ward) {
            if (is_null($ward->getId())) {
                throw new \Exception('"update" does require an ID for all instances to update');
            }
        }

        $entityManager = $this->getEntityManager();
        // iterate all the entities we got and initiate persisting them
        foreach ($wards as $ward) {
            // we will only update non-empty fields
            $currentWard = $entityManager->find(self::TARGET_ENTITY, $ward->getId());
            foreach (get_class_methods($ward) as $method) {
                // first we have to check if we have a getter method (and its original implementation)
                if (strpos($method, 'get') === 0 && strpos($method, 'DOPPELGAENGER') === false) {
                    // secondly we have to check for a non-empty value
                    $tmp = $ward->$method();
                    if (!is_null($tmp) && $tmp !== '') {
                        $substr = substr($method, 3);
                        $setterName = 'set' . $substr;
                        $currentWard->$setterName($tmp);
                    }
                }
            }
            $entityManager->merge($currentWard);
        }

        // persist the data
        $entityManager->flush();
    }

    /**
     * Deletes a certain entity
     *
     * @param string $id Id of entity to delete
     *
     * @return void
     *
     * @throws \Exception If an attempt is made to delete a super ward
     */
    public function delete($id)
    {
        // get the ward
        $entityManager = $this->getEntityManager();
        $ward = $entityManager->getReference(self::TARGET_ENTITY, $id);

        // remove the ward
        $entityManager->remove($ward);
        $entityManager->flush();
    }

    /**
     * @param string $id The ID of the ward to update the status for
     */
    public function updateStatus($id)
    {
        // load the application name
        $applicationName = $this->getApplication()->getName();

        // initialize the connection and the session
        $queue = MessageQueue::createQueue('pms/test-dispatcher');
        $connection = QueueConnectionFactory::createQueueConnection($applicationName);
        $session = $connection->createQueueSession();
        $sender = $session->createSender($queue);

        // initialize the message with the directory containing the dump files
        $message = new ArrayMessage(array(
            MessageKeys::WARD_ID => $id
        ));

        // create a new message and send it
        $sender->send($message, false);
    }

    /**
     * @Schedule(dayOfMonth = EVERY, month = EVERY, year = EVERY, second = ZERO, minute = EVERY, hour = EVERY)
     */
    protected function updateAllStatus() {
        /** @var Ward[] $wards */
        $wards = $this->findAll();
        foreach ($wards as $ward) {
            if ($ward->getCron() === 'test') {
                $this->updateStatus($ward->getId());
            }
        }
    }

    /**
     * Creates a new user instance, assumes password not provided
     *
     * @param Ward $ward
     *
     * @return Ward
     */
    public function addTest($wardId, Test $test)
    {
        /** @var Ward $ward */
        $entityManager = $this->getEntityManager();
        $ward = $entityManager->find(self::TARGET_ENTITY, $wardId);
        $ward->addTest($test);
        $entityManager->merge($ward);
        $entityManager->flush();
    }
}
