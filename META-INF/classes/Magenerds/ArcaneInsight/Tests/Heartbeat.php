<?php

/**
 * \Magenerds\ArcaneInsight\Tests\Heartbeat
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Tests;

use Magenerds\ArcaneInsight\Entities\Report;
use Magenerds\ArcaneInsight\Entities\Result;
use Magenerds\ArcaneInsight\Entities\Site;

/**
 * This is the implementation of a import message receiver.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */
class Heartbeat implements TestInterface
{

    const SEVERITY = 1;
    const RESULT_KEY = 'test:heartbeat';

    /**
     * Returns the test's severity on a scale of 1 to 10.
     * 1 being armageddon and 10 being a mere info
     *
     * @return string
     */
    public function getSeverity()
    {
        return static::SEVERITY;
    }

    /**
     * Returns the test's unique key
     *
     * @return string
     */
    public function getKey()
    {
        return static::RESULT_KEY;
    }

    /**
     * Will execute the test algorithm for a given site
     *
     * @param Site $site The site to execute the test for
     *
     * @return Result
     *
     * @throws \Exception
     */
    public function execute(Site $site)
    {
        //check, if a valid url is provided
        if (!filter_var($site->getUrl(), FILTER_VALIDATE_URL)) {
            throw new \Exception(sprintf('The site URL %s does not seem to be valid', $site->getUrl()));
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $site->getUrl());
        curl_setopt($ch, CURLOPT_USERAGENT, 'Arcane Insight Heartbeat Spy');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSLVERSION, 3);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_exec($ch);
        //echo curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = new Result();
        $result->setReport(new Report());
        $result->setSeverity(self::SEVERITY);
        $result->setKey($this->getKey());
        $result->setTimestamp(time());

        if ($httpCode >= 200 && $httpCode < 300) {
            $result->setStatus(1);
        } else {
            $result->setStatus(-1);
        }

        return $result;
    }
}
