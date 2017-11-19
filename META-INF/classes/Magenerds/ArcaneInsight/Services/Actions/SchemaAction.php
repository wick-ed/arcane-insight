<?php

/**
 * \Magenerds\ArcaneInsight\Services\Actions\SchemaAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services\Actions;

use Doctrine\ORM\Tools\SchemaTool;

/**
 * The schema action implementation that provides basic
 * database handling functionality.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @Stateless
 */
class SchemaAction
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
     * Deletes the database schema and creates it new.
     *
     * Attention: All data will be lost if this method has been invoked.
     *
     * @return void
     */
    public function createSchema()
    {
        // load the entity manager and the schema tool
        $entityManager = $this->getEntityManager();
        $schemaTool = new SchemaTool($entityManager);

        // load the class definitions
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();

        // creates the database schema
        $schemaTool->createSchema($classes);
    }

    /**
     * Deletes the database schema.
     *
     * Attention: All data will be lost if this method has been invoked.
     *
     * @return void
     */
    public function dropSchema()
    {
        // load the entity manager and the schema tool
        $entityManager = $this->getEntityManager();
        $schemaTool = new SchemaTool($entityManager);

        // load the class definitions
        $classes = $entityManager->getMetadataFactory()->getAllMetadata();

        // drop the schema if it already exists and create it new
        $schemaTool->dropSchema($classes);
    }
}
