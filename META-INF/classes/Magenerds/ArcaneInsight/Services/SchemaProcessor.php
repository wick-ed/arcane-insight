<?php

/**
 * \Magenerds\ArcaneInsight\Services\SchemaProcessor
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services;

/**
 * A singleton session bean implementation that handles the
 * schema data for Doctrine by using Doctrine ORM itself.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 *
 * @Stateless
 */
class SchemaProcessor
{

    /**
     * The schema action instance.
     *
     * @var \Doctrine\ORM\EntityManagerInterface
     * @EnterpriseBean
     */
    protected $schemaAction;

    /**
     * Deletes the database schema.
     *
     * Attention: All data will be lost if this method has been invoked.
     *
     * @return void
     */
    public function dropSchema()
    {
        $this->schemaAction->dropSchema();
    }

    /**
     * Creates the database schema.
     *
     * @return void
     */
    public function createSchema()
    {
        $this->schemaAction->createSchema();
    }
}
