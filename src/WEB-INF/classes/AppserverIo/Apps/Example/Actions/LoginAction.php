<?php

/**
 * AppserverIo\Apps\Example\Actions\LoginAction
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 */

namespace AppserverIo\Apps\Example\Actions;

use AppserverIo\Routlt\BaseAction;
use AppserverIo\Routlt\ActionInterface;
use AppserverIo\Routlt\Util\Validateable;
use AppserverIo\Apps\Example\Utils\ViewHelper;
use AppserverIo\Apps\Example\Utils\RequestKeys;
use AppserverIo\Apps\Example\Utils\SessionKeys;
use AppserverIo\Apps\Example\Exceptions\LoginException;
use AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface;
use AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface;

/**
 * Example servlet implementation that validates passed user credentials against
 * persistence container proxy and stores the user data in the session.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/appserver-io-apps/example
 * @link      http://www.appserver.io
 *
 * @Path(name="/login")
 *
 * @Results({
 *     @Result(name="input", result="/dhtml/login.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult"),
 *     @Result(name="failure", result="/dhtml/login.dhtml", type="AppserverIo\Routlt\Results\ServletDispatcherResult")
 * })
 */
class LoginAction extends BaseAction implements Validateable
{

    /**
     * The UserProcessor instance to handle the login functionality.
     *
     * @var \AppserverIo\Apps\Example\Services\UserProcessor
     * @EnterpriseBean
     */
    protected $userProcessor;

    /**
     * The username entered by the user.
     *
     * @var string
     */
    protected $username;

    /**
     * The password entered by the user.
     *
     * @var string
     */
    protected $password;

    /**
     * Returns the ImportProcessor instance to handle the login functionality.
     *
     * @return \AppserverIo\RemoteMethodInvocation\RemoteObjectInterface The instance
     */
    public function getUserProcessor()
    {
        return $this->userProcessor;
    }

    /**
     * Initializes the username from the request parameters.
     *
     * @param string $username The username entered by the user
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }


    /**
     * Returns the username entered by the user.
     *
     * @return string|null The user's username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Initializes the password from the request parameters.
     *
     * @param string $password The password entered by the user
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Returns the password entered by the user.
     *
     * @return string|null The user's password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * The validation method that implements the action's validation method.
     *
     * @return void
     * @see \AppserverIo\Routlt\Util\Validateable::validate()
     */
    public function validate()
    {

        // check if the necessary params has been specified and are valid
        if ($this->getUsername() == null) {
            $this->addFieldError(RequestKeys::USERNAME, sprintf('Please enter a valid %s', RequestKeys::USERNAME));
        }

        // check if the necessary params has been specified and are valid
        if ($this->getPassword() == null) {
            $this->addFieldError(RequestKeys::PASSWORD, sprintf('Please enter a valid %s', RequestKeys::PASSWORD));
        }
    }

    /**
     * Loads the sample entity with the sample ID found in the request and attaches
     * it to the servlet context ready to be rendered by the template.
     *
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletRequestInterface  $servletRequest  The request instance
     * @param \AppserverIo\Psr\Servlet\Http\HttpServletResponseInterface $servletResponse The response instance
     *
     * @return string|null The action result
     * @see \AppserverIo\Apps\Example\Servlets\IndexServlet::indexAction()
     */
    public function perform(HttpServletRequestInterface $servletRequest, HttpServletResponseInterface $servletResponse)
    {

        try {
            // start the session, because we need a session-ID for our stateful session bean
            $session = ViewHelper::singleton()->getLoginSession($servletRequest, true);
            $session->start();

            // try to login, using the session bean
            $this->getUserProcessor()->login($username = $this->getUsername(), $this->getPassword());

            // if successfully then add the username to the session and redirect to the overview
            $session->putData(SessionKeys::USERNAME, $username);

        } catch (LoginException $e) {
            // invalid login credentials
            $this->addFieldError('critical', "Username or Password invalid");
        }
    }
}
