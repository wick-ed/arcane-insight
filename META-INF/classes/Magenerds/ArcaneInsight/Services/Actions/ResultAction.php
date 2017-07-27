<?php

/**
 * \Magenerds\ArcaneInsight\Services\Actions\JobAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services\Actions;

use Magenerds\ArcaneInsight\Entities\Result;

/**
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 *
 * @Stateless
 */
class ResultAction extends AbstractDatabaseQueryAction
{

    //<editor-fold desc="Class constants">
    /**
     * The entity type this bean is primarily targeting
     *
     * @var string TARGET_ENTITY
     */
    const TARGET_ENTITY = 'Magenerds\ArcaneInsight\Entities\Result';
    //</editor-fold>

    /**
     * The Doctrine EntityManager instance.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     * @PersistenceUnit(unitName="ArcaneInsightEntityManager")
     */
    protected $entityManager;

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
     * Returns the initialized Doctrine entity manager.
     *
     * @return \Doctrine\ORM\EntityManagerInterface The initialized Doctrine entity manager
     */
    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * Loads all or only a certain result entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return array|Result
     */
    public function read($id = null)
    {
        if (is_null($id)) {
            $repository = $this->getEntityManager()->getRepository($this->getTargetEntity());
            $results = $repository->findAll();
        } else {
            $results = $this->getEntityManager()->find($this->getTargetEntity(), $id);

        }

        // sanitize output
        if (is_null($results)) {
            $results = array();
        }

        // return what we got
        return $results;
    }

    /**
     * Loads all or only a certain user entity
     *
     * @param Result $result The result to create
     *
     * @return void
     */
    public function create(Result $result)
    {
        // persist the data
        $entityManager = $this->getEntityManager();
        $entityManager->merge($result);
        $entityManager->flush();
    }
}
