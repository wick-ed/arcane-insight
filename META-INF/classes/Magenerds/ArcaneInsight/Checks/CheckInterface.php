<?php

/**
 * \Magenerds\ArcaneInsight\Checks\CheckInterface
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Checks;

use Magenerds\ArcaneInsight\Entities\Result;
use Magenerds\ArcaneInsight\Entities\Site;

/**
 * This is the implementation of a import message receiver.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */
interface CheckInterface
{

    /**
     * Will execute the check algorithm for a given site
     *
     * @param Site $site The site to execute the check for
     *
     * @return Result
     *
     * @throws \Exception
     */
    public function execute(Site $site);
}
