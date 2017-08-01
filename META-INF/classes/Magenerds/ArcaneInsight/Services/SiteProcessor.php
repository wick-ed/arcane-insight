<?php

/**
 * \Magenerds\ArcaneInsight\Services\SiteProcessor
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Services;

use AppserverIo\RemoteMethodInvocation\RemoteObjectInterface;
use Doctrine\ORM\EntityManagerInterface;
use Magenerds\ArcaneInsight\Entities\Site;
use Magenerds\ArcaneInsight\Entities\Status;
use Magenerds\ArcaneInsight\Services\Actions\SiteAction;
use Magenerds\ArcaneInsight\Services\Actions\StatusAction;

/**
 *
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 *
 * @Stateless
 */
class SiteProcessor
{

    /**
     * The site action
     *
     * @var EntityManagerInterface|SiteAction
     * @EnterpriseBean
     */
    protected $siteAction;

    /**
     * The site action
     *
     * @var EntityManagerInterface|StatusAction
     * @EnterpriseBean
     */
    protected $statusAction;

    /**
     * Returns the initialized Doctrine entity manager.
     *
     * @return RemoteObjectInterface|SiteAction The initialized Doctrine entity manager
     */
    protected function getSiteAction()
    {
        return $this->siteAction;
    }

    /**
     * Creates a new user instance, assumes password not provided
     *
     * @param Site $site
     *
     * @return Site
     */
    public function create(Site $site)
    {
        return $this->getSiteAction()->create($site);
    }

    /**
     * Loads all or only a certain user entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return array|Site
     */
    public function read($id = null)
    {
        return $this->getSiteAction()->read($id);
    }

    /**
     * Updates a collection of given users
     *
     * @param Site[] $sites
     *
     * @return Site
     */
    public function update(array $sites)
    {
        return $this->getSiteAction()->update($sites);
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
        $this->getSiteAction()->delete($id);
    }

    /**
     * Loads all or only a certain user entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return Status[]
     */
    public function getStatus($id = null)
    {
        return $this->statusAction->readBySiteId($id);
    }
}
