<?php

/**
 * \Magenerds\ArcaneInsight\Services\WardProcessor
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services;

use AppserverIo\Messaging\ArrayMessage;
use AppserverIo\Messaging\MessageQueue;
use AppserverIo\Messaging\QueueConnectionFactory;
use AppserverIo\RemoteMethodInvocation\RemoteObjectInterface;
use Doctrine\ORM\EntityManagerInterface;
use Magenerds\ArcaneInsight\Entities\Test;
use Magenerds\ArcaneInsight\Entities\Ward;
use Magenerds\ArcaneInsight\Entities\Status;
use Magenerds\ArcaneInsight\Services\Actions\WardAction;
use Magenerds\ArcaneInsight\Services\Actions\StatusAction;
use Magenerds\ArcaneInsight\Util\MessageKeys;

/**
 *
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @Stateless
 */
class WardProcessor
{

    /**
     * The ward action
     *
     * @var EntityManagerInterface|WardAction
     * @EnterpriseBean
     */
    protected $wardAction;

    /**
     * Returns the initialized Doctrine entity manager.
     *
     * @return RemoteObjectInterface|WardAction The initialized Doctrine entity manager
     */
    protected function getWardAction()
    {
        return $this->wardAction;
    }

    /**
     * Creates a new user instance, assumes password not provided
     *
     * @param Ward $ward
     *
     * @return Ward
     */
    public function create(Ward $ward)
    {
        return $this->getWardAction()->create($ward);
    }

    /**
     * Loads all or only a certain user entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return array|Ward
     */
    public function read($id = null)
    {
        return $this->getWardAction()->read($id);
    }

    /**
     * Updates a collection of given users
     *
     * @param Ward[] $wards
     *
     * @return Ward
     */
    public function update(array $wards)
    {
        return $this->getWardAction()->update($wards);
    }

    /**
     * Deletes a certain entity
     *
     * @param string $id Id of entity to delete
     *
     * @return void
     */
    public function delete($id)
    {
        $this->getWardAction()->delete($id);
    }

    /**
     * @param string $id The ID of the ward to update the status for
     */
    public function updateStatus($id)
    {
        $this->getWardAction()->updateStatus($id);
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
        return $this->getWardAction()->addTest($wardId, $test);
    }
}
