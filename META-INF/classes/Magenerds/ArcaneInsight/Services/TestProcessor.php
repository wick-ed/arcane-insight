<?php

/**
 * \Magenerds\ArcaneInsight\Services\TestProcessor
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services;

use AppserverIo\RemoteMethodInvocation\RemoteObjectInterface;
use Magenerds\ArcaneInsight\Entities\Test;
use Magenerds\ArcaneInsight\Entities\Result;
use Magenerds\ArcaneInsight\Services\Actions\TestAction;

/**
 *
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @Stateless
 */
class TestProcessor
{

    /**
     * The result action
     *
     * @var RemoteObjectInterface|TestAction
     * @EnterpriseBean
     */
    protected $testAction;

    /**
     * Returns the initialized Doctrine entity manager.
     *
     * @return RemoteObjectInterface|TestAction The initialized Doctrine entity manager
     */
    protected function getTestAction()
    {
        return $this->testAction;
    }

    /**
     * Loads all or only a certain user entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return array|Result
     */
    public function read($id = null)
    {
        return $this->getTestAction()->read($id);
    }

    /**
     * Loads all or only a certain user entity
     *
     * @param Test $test The test to create
     *
     * @return array|Test
     */
    public function create(Test $test)
    {
        return $this->getTestAction()->create($test);
    }

    /**
     *
     */
    public function syncKnownTests()
    {
        $this->getTestAction()->syncKnownTests();
    }
}
