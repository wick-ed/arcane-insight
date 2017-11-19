<?php

/**
 * \Magenerds\ArcaneInsight\Actions\AbstractRestfulAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Actions;

use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\ContractExceptionInterface;
use AppserverIo\Psr\MetaobjectProtocol\Dbc\BrokenPreconditionException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Abstract action to extract information from a RESTful style URI
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */
abstract class AbstractRestfulAction extends AbstractDispatchAction
{

    /**
     * The name of the primary REST resource
     *
     * @var string|null $primaryEntity
     */
    protected $primaryEntity;

    /**
     * The id of the primary REST resource
     *
     * @var string|null $primaryId
     */
    protected $primaryId;

    /**
     * The id of the secondary REST resource
     *
     * @var string|null $secondaryEntity
     */
    protected $secondaryEntity;

    /**
     * The id of the secondary REST resource
     *
     * @var string|null $secondaryId
     */
    protected $secondaryId;

    /**
     * Pairs of resources extracted from the path info.
     * Has the form <RESOURCE> => <ID>
     *
     * @var array $resourcePairs
     */
    protected $resourcePairs;

    /**
     * Mapping which helps to resolve entities plural form
     *
     * @var array $entitiesPluralMapping
     */
    protected $entitiesPluralMapping = array(
        'tests' => 'test',
        'wards' => 'ward',
    );

    /**
     * Getter for the primaryEntity member
     *
     * @return string|null
     */
    public function getPrimaryEntity()
    {
        return $this->primaryEntity;
    }

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
     * Getter for the secondaryEntity member
     *
     * @return string|null
     */
    public function getSecondaryEntity()
    {
        return $this->secondaryEntity;
    }

    /**
     * Getter for the secondaryId member
     *
     * @return string|null
     */
    public function getSecondaryId()
    {
        return $this->secondaryId;
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
            } else {
                $this->resourcePairs[$urlParts[$i]] = null;
            }
        }

        // we store the primary entity separately for simplicity reasons
        if ($partsCount > 1) {
            $this->primaryEntity = $urlParts[1];
        }

        // we store the primary id separately for simplicity reasons
        if ($partsCount > 2) {
            $this->primaryId = $urlParts[2];
        }

        // we might also have a sub-entity to work with
        if ($partsCount > 3) {
            $this->secondaryEntity = $urlParts[3];
        }

        // ... and an ID for this sub-entity
        if ($partsCount > 4) {
            $this->secondaryId = $urlParts[4];
        }

        // fall-through to the parent
        return parent::perform($servletRequest, $servletResponse);
    }

    /**
     * Converts an object to a CRUD entity
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
                    // we might have an array at our hands
                    if (is_array($value)) {
                    $tmpValue = $value;
                    $value = new ArrayCollection();
                        // build up the potential sub entity class name but be aware that the property might be in the plural form
                        $potentialSubEntity = substr($entityClass, 0, (strrpos($entityClass, '\\') + 1));
                        if (isset($this->entitiesPluralMapping[$property])) {
                            $potentialSubEntity .= ucfirst($this->entitiesPluralMapping[$property]);
                        } else {
                            $potentialSubEntity .= ucfirst($property);
                        }
                        // if we found something we can start converting
                        if (class_exists($potentialSubEntity)) {
                            foreach ($tmpValue as $key => $subValue) {
                                $value->add($this->convertToEntity($subValue, $potentialSubEntity));
                            }
                        }
                    } elseif (is_object($value)) {
                        // we have single object
                        $potentialSubEntity = substr($entityClass, 0, (strrpos($entityClass, '\\') + 1)) . ucfirst($property);
                        if (class_exists($potentialSubEntity)) {
                            $value = $this->convertToEntity($value, $potentialSubEntity);
                        }
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
