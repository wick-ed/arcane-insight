<?php

/**
 * \Magenerds\ArcaneInsight\Services\Actions\SiteAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services\Actions;

use Magenerds\ArcaneInsight\Entities\Site;

/**
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 *
 * @Stateless
 */
class SiteAction extends AbstractDatabaseQueryAction
{

    //<editor-fold desc="Class constants">
    /**
     * The entity type this bean is primarily targeting
     *
     * @var string TARGET_ENTITY
     */
    const TARGET_ENTITY = 'Magenerds\ArcaneInsight\Entities\Site';
    //</editor-fold>

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
     * Loads all or only a certain site entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return Site[]|Site
     */
    public function read($id = null)
    {
        if (is_null($id)) {
            $sites = $this->findAll();
        } else {
            $sites = $this->findById($id);
        }

        // sanitize output
        if (is_null($sites)) {
            $sites = array();
        }

        // return what we got
        return $sites;
    }
}
