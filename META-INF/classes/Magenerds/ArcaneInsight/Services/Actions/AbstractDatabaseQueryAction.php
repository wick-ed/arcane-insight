<?php

/**
 * \Magenerds\ArcaneInsight\Services\Actions\AbstractDatabaseQueryAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services\Actions;

/**
 * Abstract base class for action beans which can query the database in a direct way
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */
abstract class AbstractDatabaseQueryAction
{

    /**
     * The Doctrine EntityManager instance.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     * @PersistenceUnit(unitName="ArcaneInsightEntityManager")
     */
    protected $entityManager;

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
     * Returns the class name of the entity this bean primarily deals with
     *
     * @return string
     */
    abstract public function getTargetEntity();

    /**
     * Returns an array with all entities
     *
     * @return array The array with the entities
     */
    public function findAll()
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository($this->getTargetEntity());
        return $repository->findAll();
    }

    /**
     * Returns an array with the entities matching the passed query
     *
     * @param array $query The query matching the entities
     *
     * @return mixed The collection with the entities matching the query
     */
    public function findBy(array $query)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository($this->getTargetEntity());
        return $repository->findOneBy($query);
    }

    /**
     * Returns an array with all entities
     *
     * @param string $id The instance ID
     *
     * @return object|null The found entity
     */
    public function findById($id)
    {
        $entityManager = $this->getEntityManager();
        $repository = $entityManager->getRepository($this->getTargetEntity());
        return $repository->findOneBy(array('id' => $id));
    }
}
