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
     * Persists a collection of sites
     *
     * @param Site $site          Site entity to persist
     *
     * @return Site
     */
    public function create(Site $site)
    {
        // persist the data
        $entityManager = $this->getEntityManager();
        /** @var Site $site */
        $site = $entityManager->merge($site);
        $entityManager->flush();

        // return the new site
        return $site;
    }

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

    /**
     *
     *
     * @param Site[] $sites
     *
     * @return void
     */
    public function update(array $sites)
    {
        // check for IDs
        foreach ($sites as $site) {
            if (is_null($site->getId())) {
                throw new \Exception('"update" does require an ID for all instances to update');
            }
        }

        $entityManager = $this->getEntityManager();
        // iterate all the entities we got and initiate persisting them
        foreach ($sites as $site) {
            // we will only update non-empty fields
            $currentSite = $entityManager->find(self::TARGET_ENTITY, $site->getId());
            foreach (get_class_methods($site) as $method) {
                // first we have to check if we have a getter method (and its original implementation)
                if (strpos($method, 'get') === 0 && strpos($method, 'DOPPELGAENGER') === false) {
                    // secondly we have to check for a non-empty value
                    $tmp = $site->$method();
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
     *
     * @throws \Exception If an attempt is made to delete a super site
     */
    public function delete($id)
    {
        // get the site
        $entityManager = $this->getEntityManager();
        $site = $entityManager->getReference(self::TARGET_ENTITY, $id);

        // remove the site
        $entityManager->remove($site);
        $entityManager->flush();
    }
}
