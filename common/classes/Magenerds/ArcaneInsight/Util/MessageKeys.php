<?php
/**
 * \Magenerds\ArcaneInsight\Util\MessageKeys
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Util;

/**
 * Message keys used to identify MQ message contents
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
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
     * The key for the check name
     *
     * @return string
     */
    const CHECK = 'check';
}
