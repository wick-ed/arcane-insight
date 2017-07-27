<?php

/**
 * \Magenerds\ArcaneInsight\Actions\AbstractRestfulAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Actions;

use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\ContractExceptionInterface;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException;

/**
 * Abstract action to extract information from a RESTful style URI
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */
abstract class AbstractRestfulAction extends AbstractDispatchAction
{

    /**
     * The id of the primary REST resource
     *
     * @var string|null $primaryId
     */
    protected $primaryId;

    /**
     * Pairs of resources extracted from the path info.
     * Has the form <RESOURCE> => <ID>
     *
     * @var array $resourcePairs
     */
    protected $resourcePairs;

    /**
     * Getter for the primaryId member
     *
     * @return string|null
     */
    public function getPrimaryId()
    {
        return $this->primaryId;
    }

    /**
     * Getter for the resourcePairs member
     *
     * @return array
     */
    public function getResourcePairs()
    {
        return $this->resourcePairs;
    }

    /**
     * Getter for the entity class associated with the CRUD action
     *
     * @return string The class name of the entity in question
     */
    abstract public function getEntityClass();

    /**
     * Implemented to comply with the interface.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     */
    public function perform(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        // get the URL parts as we need them for id retrieval
        $urlParts = explode('/', $servletRequest->getPathInfo());
        // prepare the resource pairs
        $partsCount = count($urlParts);
        for ($i = 1; $i < $partsCount; $i = $i + 2) {
            if (isset($urlParts[$i + 1])) {
                $this->resourcePairs[$urlParts[$i]] = $urlParts[$i + 1];
            }
        }

        // we store the primary id separately for simplicity reasons
        if ($partsCount > 2) {
            $this->primaryId = $urlParts[2];
        }

        // fall-through to the parent
        return parent::perform($servletRequest, $servletResponse);
    }

    /**
     * Converts and object to a CRUD entity
     *
     * @param \stdClass   $rawObject   The object to convert
     * @param string|null $entityClass A entity class to convert to
     *
     * @return object The resulting entity instance
     *
     * @throws \AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException If validation failes
     */
    protected function convertToEntity(\stdClass $rawObject, $entityClass = null)
    {
        // prepare a target instance
        if (is_null($entityClass)) {
            $entityClass = $this->getEntityClass();
        }
        $resultInstance = new $entityClass();

        // iterate the properties and check what we can set
        $validationErrors = array();
        foreach (get_object_vars($rawObject) as $property => $value) {
            $method = 'set' . ucfirst($property);
            if (method_exists($resultInstance, $method)) {
                // try the setter, but be aware of validation errors
                try {
                    // check if we have to convert the value first
                    $potentialSubEntity = substr($entityClass, 0, (strrpos($entityClass, '\\') + 1)) . ucfirst($property);
                    if (class_exists($potentialSubEntity)) {
                        $value = $this->convertToEntity($value, $potentialSubEntity);
                    }
                    $resultInstance->$method($value);
                } catch (ContractExceptionInterface $e) {
                    $validationErrors[] = $e->getMessage();
                }
            }
        }

        // re-throw a validation exception if there is reason to
        if (!empty($validationErrors)) {
            throw new BrokenPreconditionException(implode(' and', $validationErrors));
        }

        return $resultInstance;
    }
}
