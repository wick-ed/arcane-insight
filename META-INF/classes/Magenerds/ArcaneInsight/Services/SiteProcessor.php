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
     * Loads all or only a certain user entity
     *
     * @param string|null $id Id of entity to load
     *
     * @return array|Site
     */
    public function read($id = null)
    {
        return $this->siteAction->read($id);
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
