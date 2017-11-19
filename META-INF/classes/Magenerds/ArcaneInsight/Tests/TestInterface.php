<?php

/**
 * \Magenerds\ArcaneInsight\Tests\TestInterface
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Tests;

use Magenerds\ArcaneInsight\Entities\Result;
use Magenerds\ArcaneInsight\Entities\Site;

/**
 * This is the implementation of a import message receiver.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */
interface TestInterface
{

    /**
     * Returns the test's severity on a scale of 1 to 10.
     * 1 being armageddon and 10 being a mere info
     *
     * @return string
     */
    public function getSeverity();

    /**
     * Returns the test's unique key
     *
     * @return string
     */
    public function getKey();

    /**
     * Will execute the test algorithm for a given site
     *
     * @param Site $site The site to execute the test for
     *
     * @return Result
     *
     * @throws \Exception
     */
    public function execute(Site $site);
}
