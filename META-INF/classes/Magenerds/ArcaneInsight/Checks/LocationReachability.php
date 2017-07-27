<?php

/**
 * \Magenerds\ArcaneInsight\Checks\LocationReachability
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Checks;

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
 * @link      https://github.com/magenerds/arcane-insight
 */
class LocationReachability implements CheckInterface
{

    const SEVERITY = 2;
    const RESULT_KEY = 'check:location-reachability';

    /**
     * Will execute the check algorithm for a given site
     *
     * @param Site $site The site to execute the check for
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
