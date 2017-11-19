<?php

/**
 * \Magenerds\ArcaneInsight\Tests\LocationReachability
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Tests;

use Magenerds\ArcaneInsight\Entities\Report;
use Magenerds\ArcaneInsight\Entities\Result;
use Magenerds\ArcaneInsight\Entities\Site;
use MageScan\Command\Scan\UnreachableCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

/**
 * This is the implementation of a import message receiver.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/wick-ed/arcane-insight
 */
class LocationReachability implements TestInterface
{

    const SEVERITY = 2;
    const RESULT_KEY = 'test:magento1:location-reachability';

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

        $application = new Application('MageScan');
        $application->add(new UnreachableCommand());
        $application->setAutoExit(false);

        $input = new ArrayInput(array(
            'command' => 'scan:unreachable',
            'url' => $site->getUrl()
        ));
        // You can use NullOutput() if you don't need the output
        $output = new BufferedOutput();
        $application->run($input, $output);

        // return the output, don't use if you used NullOutput()
        $content = $output->fetch();

        $report = new Report();
        $report->setLog($content);
        $report->setTimestamp(time());

        $foundFailure = preg_match('/\|\s*Fail\s*\|/', $content);
        $status = 0;
        if ($foundFailure === 1) {
            $status = -1;
        } elseif ($foundFailure === 0) {
            $status = 1;
        }

        $result = new Result();
        $result->setStatus($status);
        $result->setReport($report);
        $result->setSeverity(self::SEVERITY);
        $result->setKey(self::RESULT_KEY);
        $result->setTimestamp(time());

        return $result;
    }
}
