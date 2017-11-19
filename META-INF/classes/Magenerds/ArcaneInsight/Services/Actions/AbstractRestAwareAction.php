<?php

/**
 * \Magenerds\ArcaneInsight\Services\Actions\AbstractRestAwareAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services\Actions;

/**
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */
abstract class AbstractRestAwareAction extends AbstractDatabaseQueryAction
{

    /**
     * Persists a collection of entities
     *
     * @param mixed $entity The entity to persist
     *
     * @return mixed
     */
    public function create($entity)
    {
        // persist the data
        $entityManager = $this->getEntityManager();
        /** @var object $entity */
        $entity = $entityManager->merge($entity);
        $entityManager->flush();

        // return the new site
        return $entity;
    }

    /**
     * Loads all or only a certain site entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return object[]|object
     */
    public function read($id = null)
    {
        if (is_null($id)) {
            $entities = $this->findAll();
        } else {
            $entities = $this->findById($id);
        }

        // sanitize output
        if (is_null($entities)) {
            $entities = array();
        }

        // return what we got
        return $entities;
    }

    /**
     *
     *
     * @param object[] $entities
     *
     * @return void
     */
    public function update(array $entities)
    {
        // check for IDs
        foreach ($entities as $entity) {
            if (is_null($entity->getId())) {
                throw new \Exception('"update" does require an ID for all instances to update');
            }
        }

        $entityManager = $this->getEntityManager();
        // iterate all the entities we got and initiate persisting them
        foreach ($entities as $entity) {
            // we will only update non-empty fields
            $currentSite = $entityManager->find($this->getTargetEntity(), $entity->getId());
            foreach (get_class_methods($entity) as $method) {
                // first we have to check if we have a getter method (and its original implementation)
                if (strpos($method, 'get') === 0 && strpos($method, 'DOPPELGAENGER') === false) {
                    // secondly we have to check for a non-empty value
                    $tmp = $entity->$method();
                    if (!is_null($tmp) && $tmp !== '') {
                        $substr = substr($method, 3);
                        $setterName = 'set' . $substr;
                        $currentSite->$setterName($tmp);
                    }
                }
            }
            $entityManager->merge($currentSite);
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
     */
    public function delete($id)
    {
        // get the site
        $entityManager = $this->getEntityManager();
        $entity = $entityManager->getReference($this->getTargetEntity(), $id);

        // remove the site
        $entityManager->remove($entity);
        $entityManager->flush();
    }
}
