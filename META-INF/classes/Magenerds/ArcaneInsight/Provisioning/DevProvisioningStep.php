<?php

/**
 * \Magenerds\ArcaneInsight\Provisioning\DevProvisioningStep
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Provisioning;

/**
 * A step implementation that prepares the application for the use within a development environment
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */
class DevProvisioningStep extends AbstractProvisioningStep
{

    /**
     * Executes the functionality for this step, in this case the execution of
     * the PHP script defined in the step configuration.
     *
     * @return void
     * @throws \Exception Is thrown if the script can't be executed
     * @see \AppserverIo\Appserver\Core\Provisioning\StepInterface::execute()
     */
    public function execute()
    {
        try {
            // log a message that provisioning starts
            $this->logStart();

            // load the schema processor of our application
            /** @var SchemaProcessorInterface $schemaProcessor */
            $schemaProcessor = $this->getApplication()->search('SchemaProcessor');

            // create the datasources anew and fill them with dummy data
            $schemaProcessor->dropSchema();
            $schemaProcessor->createSchema();

            // log a message that provisioning has been successful
            $this->logSuccess();

        } catch (\Exception $e) {
            $this->logError($e->__toString());
        }
    }
}
