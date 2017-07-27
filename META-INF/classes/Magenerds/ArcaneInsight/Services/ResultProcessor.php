<?php

/**
 * \Magenerds\ArcaneInsight\Services\ResultProcessor
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services;

use Doctrine\ORM\EntityManagerInterface;
use Magenerds\ArcaneInsight\Entities\Result;
use Magenerds\ArcaneInsight\Services\Actions\ResultAction;

/**
 *
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 *
 * @Stateless
 */
class ResultProcessor
{

    /**
     * The result action
     *
     * @var EntityManagerInterface|ResultAction
     * @EnterpriseBean
     */
    protected $resultAction;

    /**
     * Loads all or only a certain user entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return array|Result
     */
    public function read($id = null)
    {
        return $this->resultAction->read($id);
    }

    /**
     * Loads all or only a certain user entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return array|Result
     */
    public function create(Result $result)
    {
        return $this->resultAction->create($result);
    }
}
