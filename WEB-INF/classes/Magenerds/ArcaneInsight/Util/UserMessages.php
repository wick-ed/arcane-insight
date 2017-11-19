<?php

/**
 * \Magenerds\ArcaneInsight\Util\UserMessages
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Util;

/**
 * Utility class containing messages to be seen by the end user.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */
class UserMessages
{

    /**
     * Make constructor private to avoid direct initialization.
     */
    private function __construct()
    {
    }

    /**
     * Make clone method private to avoid initialization by cloning.
     *
     * @return \Magenerds\ArcaneInsight\Util\UserMessages
     */
    private function __clone()
    {
    }

    /**
     * General server error
     *
     * @return string
     */
    const SERVER_ERROR = 'Server Fehler.';

    /**
     * Resource not found error
     *
     * @return string
     */
    const NOT_FOUND = 'Resource nicht gefunden.';
}
