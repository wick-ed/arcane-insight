<?php
/**
 * \Magenerds\ArcaneInsight\Util\MessageKeys
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Util;

/**
 * Message keys used to identify MQ message contents
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */
class MessageKeys
{
    /**
     * Private to constructor to avoid instancing this class.
     */
    private function __construct()
    {
    }

    /**
     * The key for a site identifier
     *
     * @return string
     */
    const SITE = 'site';

    /**
     * The key for the test name
     *
     * @return string
     */
    const TEST = 'test';

    /**
     * The key for the ward id
     *
     * @return string
     */
    const WARD_ID = 'wardId';
}
