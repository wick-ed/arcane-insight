<?php

/**
 * \Magenerds\ArcaneInsight\Checks\Heartbeat
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
class Heartbeat implements CheckInterface
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
    public function execute(Site $site)
    {
        //check, if a valid url is provided
        if(!filter_var($site->getUrl(), FILTER_VALIDATE_URL))
        {
            throw new \Exception(sprintf('The site URL %s does not seem to be valid', $site->getUrl()));
        }

        $ch=curl_init();
        curl_setopt ($ch, CURLOPT_URL,$site->getUrl() );
        curl_setopt($ch, CURLOPT_USERAGENT, 'Arcane Insight Heartbeat Spy');
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch,CURLOPT_VERBOSE,false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch,CURLOPT_SSLVERSION,3);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_exec($ch);
        //echo curl_error($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode>=200 && $httpCode<300) {
            return true;
        } else {
            return false;
        }
    }
}
