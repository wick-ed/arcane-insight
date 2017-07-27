<?php

/**
 * \Magenerds\ArcaneInsight\Provisioning\AbstractProvisioningStep
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Provisioning;

use AppserverIo\Appserver\Provisioning\Steps\AbstractStep;

/**
 * An abstract step implementation that provides the base for different provisioning steps
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */
abstract class AbstractProvisioningStep extends AbstractStep
{

    /**
     * Will return the name of the application
     *
     * @return string
     */
    protected function getAppName()
    {
        return $this->getApplication()->getName();
    }

    /**
     * Will return the name of the application environment
     *
     * @return string
     */
    protected function getAppEnvironment()
    {
        return $this->getApplication()->getEnvironmentName();
    }

    /**
     * Logs the start of the provisioning
     *
     * @return void
     */
    protected function logStart()
    {
        $this->getApplication()->getInitialContext()->getSystemLogger()->info(
            sprintf(
                'Now start to prepare %s datasources for a %s environment using SchemaProcessor!',
                $this->getAppName(),
                $this->getAppEnvironment()
            )
        );
    }

    /**
     * Logs the success of the provisioning
     *
     * @return void
     */
    protected function logSuccess()
    {
        $this->getApplication()->getInitialContext()->getSystemLogger()->info(
            sprintf(
                'Successfully prepared %s datasources for a %s environment!',
                $this->getAppName(),
                $this->getAppEnvironment()
            )
        );
    }

    /**
     * Logs any given error
     *
     * @param string $error The error message to log
     *
     * @return void
     */
    protected function logError($error)
    {
        $this->getApplication()->getInitialContext()->getSystemLogger()->error($error);
    }
}
