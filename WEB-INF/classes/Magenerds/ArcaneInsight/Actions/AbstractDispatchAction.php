<?php

/**
 * \Magenerds\ArcaneInsight\Actions\AbstractDispatchAction
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */

namespace Magenerds\ArcaneInsight\Actions;

use JMS\Serializer\SerializerBuilder;
use AppserverIo\Http\HttpProtocol;
use AppserverIo\Routlt\DispatchAction;
use AppserverIo\Routlt\Util\EncodingAware;
use AppserverIo\Routlt\Util\DefaultHeadersAware;
use AppserverIo\Psr\Context\ContextInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;
use Doctrine\Common\Proxy\Autoloader;
use AppserverIo\Server\Dictionaries\ServerVars;
use Magenerds\ArcaneInsight\Util\UserMessages;

/**
 * Action implementation providing functionality to search the ES profiles index.
 *
 * @copyright Copyright (c) 2017 TechDivision GmbH (http://www.techdivision.com)
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @link      https://github.com/magenerds/arcane-insight
 */
abstract class AbstractDispatchAction extends DispatchAction implements EncodingAware, DefaultHeadersAware
{

    /**
     * The application instance that provides the entity manager.
     *
     * @var \AppserverIo\Psr\Application\ApplicationInterface
     * @Resource(name="ApplicationInterface")
     */
    protected $application;

    /**
     * The servlet request instance.
     *
     * @var \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface
     */
    protected $servletRequest;

    /**
     * Returns the servlet response instance.
     *
     * @return \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface The request instance
     */
    public function getServletRequest()
    {
        return $this->servletRequest;
    }

    /**
     * The application instance providing the database connection.
     *
     * @return \AppserverIo\Psr\Application\ApplicationInterface The application instance
     */
    protected function getApplication()
    {
        return $this->application;
    }

    /**
     * Getter for the session
     *
     * @param boolean $create Whether or not a new session should be created (if none present already), defaults to FALSE
     *
     * @return \AppserverIo\Psr\Servlet\Http\HttpSessionInterface|null
     *
     * @throws \Exception
     */
    public function getSession($create = false)
    {
        // try to load the servlet request
        $servletRequest = $this->getServletRequest();
        if ($servletRequest == null) {
            throw new \Exception('Can\'t find necessary servlet request instance');
        }
        // return the session
        return $servletRequest->getSession($create);
    }

    /**
     * Destroy current session
     *
     * @param string $explicitReason The Reason for destroying the session
     *
     * @return void
     */
    public function destroySession($explicitReason)
    {
        $session = $this->getSession();
        if (!is_null($session)) {
            $session->destroy('Destroy User-Session: ' . $explicitReason);
        }
    }

    /**
     * Return the current host and protocol
     *
     * @param boolean $forceTls Whether or not to force SSL/TLS. Defaults to TRUE
     *
     * @return string
     */
    public function getCurrentHost($forceTls = true)
    {
        $request = $this->getServletRequest();
        $host = $request->getServerVar(ServerVars::HTTP_HOST);
        $protocol = 'http';
        if ($request->getServerVar(ServerVars::HTTPS) === 'on' || $forceTls) {
            $protocol = 'https';
        }
        return $protocol . '://' . $host;
    }

    /**
     * Implemented to comply witht the interface.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     */
    public function perform(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {
        //set servlet request
        $this->servletRequest = $servletRequest;

        // invoke the requested action method
        $result = parent::perform($servletRequest, $servletResponse);

        // an error will result in a 500 per default (if nothing appropriately has been set)
        if ($this->hasErrors() && $servletResponse->getStatusCode() < 300) {
            $servletResponse->setStatusCode(500);
        }

        return $result;
    }

    /**
     * Returns TRUE if the action has default headers.
     *
     * @return boolean TRUE if the action has default headers, else FALSE
     */
    public function hasDefaultHeaders()
    {
        return sizeof($this->getDefaultHeaders()) > 0;
    }

    /**
     * Returns the array with action's default headers.
     *
     * @return array The array with action's default headers
     */
    public function getDefaultHeaders()
    {
        return array(HttpProtocol::HEADER_CONTENT_TYPE => 'application/json');
    }

    /**
     * Encodes the passed data, to JSON format for example, and returns it.
     *
     * @param mixed $data The data to be encoded
     *
     * @return string The encoded data.
     */
    public function encode($data)
    {
        return SerializerBuilder::create()->build()->serialize($data, 'json');
    }

    /**
     * Will filter error messages based on the application environment
     *
     * @param string  $devMessage The message which will be returned if the environment is intended for development
     * @param integer $code       The HTTP code belonging to the response message, 500 by default
     *
     * @return string
     */
    protected function filterResponseMessage($devMessage, $code = 500)
    {
        if ($this->getApplication()->getEnvironmentName() !== 'dev' && $this->getApplication()->getEnvironmentName() !== 'test') {
            switch ($code) {
                case 404:
                    return UserMessages::NOT_FOUND;
                    break;
                case 500:
                default:
                    return UserMessages::SERVER_ERROR;
                    break;
            }
        }
        return $devMessage;
    }
}
