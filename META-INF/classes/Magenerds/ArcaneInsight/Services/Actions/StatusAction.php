<?php

/**
 * \Magenerds\ArcaneInsight\Services\Actions\StatusAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services\Actions;

use Magenerds\ArcaneInsight\Entities\Site;
use Magenerds\ArcaneInsight\Entities\Status;

/**
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 *
 * @Stateless
 */
class StatusAction extends AbstractDatabaseQueryAction
{

    //<editor-fold desc="Class constants">
    /**
     * The entity type this bean is primarily targeting
     *
     * @var string TARGET_ENTITY
     */
    const TARGET_ENTITY = 'Magenerds\ArcaneInsight\Entities\Status';
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
     * @return Status[]|Status
     */
    public function read($id = null)
    {
        if (is_null($id)) {
            $status = $this->findAll();
        } else {
            $status = $this->findById($id);
        }

        // sanitize output
        if (is_null($status)) {
            $status = array();
        }

        // return what we got
        return $status;
    }

    /**
     * Loads all or only a certain site entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return Status[]|Status
     */
    public function readBySiteId($id = null)
    {
        if (is_null($id)) {
            $status = $this->read();
            foreach ($status as $key => $singelStatus) {
                if (is_null($singelStatus->getSite())) {
                    unset($status[$key]);
                }
            }
        } else {
            $status = $this->findBy(array('site_id' => $id));
        }

        // sanitize output
        if (is_null($status)) {
            $status = array();
        }

        // return what we got
        return $status;
    }
}
