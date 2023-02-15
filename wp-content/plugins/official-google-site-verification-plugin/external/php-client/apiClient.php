<?php
/**
 * This file is part of the Google Verification Wordpress Plugin.
 *
 * The Google Verification Wordpress Plugin is free software:
 * you can redistribute it and/or modify it under the terms of the
 * GNU General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or (at your option) any later version.

 * The Google Site Verification Wordpress Plugin is distributed in the hope
 * that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with the Google Site Verification Wordpress Plugin.
 * If not, see <http://www.gnu.org/licenses/>.
 */

// Check for the required json and curl extensions, the google api php client won't function without
if (! function_exists('curl_init')) {
  throw new Exception('The Google PHP API Library needs the CURL PHP extension');
}
if (! function_exists('json_decode')) {
  throw new Exception('The Google PHP API Library needs the JSON PHP extension');
}

// hack around with the include paths a bit so the library 'just works'
$cwd = dirname(__FILE__);
set_include_path("$cwd" . PATH_SEPARATOR . ":" . get_include_path());

require_once "config.php";
// If a local configuration file is found, merge it's values with the default configuration
if (file_exists($cwd . '/local_config.php')) {
  $defaultConfig = $apiConfig;
  require_once ($cwd . '/local_config.php');
  $apiConfig = array_merge($defaultConfig, $apiConfig);
}

// Include the top level classes, they each include their own dependencies
require_once "auth/apiAuth.php";
require_once "cache/apiCache.php";
require_once "io/apiIO.php";
require_once "service/apiService.php";

// Exceptions that the Google PHP API Library can throw
class apiException extends Exception {}
class apiAuthException extends apiException {}
class apiCacheException extends apiException {}
class apiIOException extends apiException {}
class apiServiceException extends apiException {}

/* global array of type handlers, used by the api request executers to parse results
 * maps the type strings ('buzz#activity') to a class representation (buzzAcvitityModel) which will be automatically triggered on input
 */
global $apiTypeHandlers;
$apiTypeHandlers = array();

/**
 * The Google API Client
 * http://code.google.com/p/google-api-php-client/
 *
 * @author Chris Chabot <chabotc@google.com>
 */
class apiClient {

  // the version of the discovery mechanism this class is meant to work with
  const discoveryVersion = 'v0.3';

  // worker classes
  protected $auth;
  protected $io;
  protected $cache;
  protected $scopes = false;

  // definitions of services that are discover()'rd
  protected $services = array();

  // Used to track authenticated state, can't discover services after doing authenticate()
  private $authenticated = false;

  private $defaultService = array(
      'authorization_token_url' => 'https://www.google.com/accounts/OAuthAuthorizeToken',
      'request_token_url' => 'https://www.google.com/accounts/OAuthGetRequestToken',
      'access_token_url' => 'https://www.google.com/accounts/OAuthGetAccessToken');

  public function __construct() {
    global $apiConfig;
    // Create our worker classes
    $this->cache = new $apiConfig['cacheClass']();
    $this->auth = new $apiConfig['authClass']();
    $this->io = new $apiConfig['ioClass']($this->cache, $this->auth);
    $this->auth->setIo($this->io);
  }

  public function discover($service, $version = 'v1') {
    $this->addService($service, $version);
    $this->$service = $this->discoverService($service, $this->services[$service]['discoveryURI']);
    return $this->$service;
  }

  /**
   * Add a service
   */
  public function addService($service, $version) {
    global $apiConfig;
    if ($this->authenticated) {
      // Adding services after being authenticated, since the oauth scope is already set (so you wouldn't have access to that data)
      throw new apiException("Can't add services after having authenticated");
    }
    $this->services[$service] = $this->defaultService;
    if (isset($apiConfig['services'][$service])) {
      // Merge the service descriptor with the default values
      $this->services[$service] = array_merge($this->services[$service], $apiConfig['services'][$service]);
    }
    $this->services[$service]['discoveryURI'] = $apiConfig['basePath'] . '/discovery/' . self::discoveryVersion . '/describe/' . urlencode($service) . '/' . urlencode($version);
  }

  public function authenticate() {
    $this->authenticated = true;
    $service = $this->defaultService;
    $scopes = array();
    if ($this->scopes) {
      $scopes = $this->scopes;
    } else {
      foreach ($this->services as $key => $val) {
        if (isset($val['scope'])) {
          $scopes[] = $val['scope'];
        } else {
          $scopes[] = 'https://www.googleapis.com/auth/' . $key;
        }
        unset($val['discoveryURI']);
        unset($val['scope']);
        $service = array_merge($service, $val);
      }
    }
    $service['scope'] = implode(' ', $scopes);
    return $this->auth->authenticate($this->cache, $this->io, $service);
  }

  /**
   * Set the OAuth access token using the string that resulted from calling authenticate()
   * @param (serialized) string $accessToken
   */
  public function setAccessToken($accessToken) {
    $this->auth->setAccessToken($accessToken);
  }

  public function getAccessToken() {
    return $this->auth->getAccessToken();
  }

  /**
   * Set the developer key to use, these are obtained through the API Console
   */
  public function setDeveloperKey($developerKey) {
    $this->auth->setDeveloperKey($developerKey);
  }

  /**
   * This function allows you to overrule the automatically generated scopes, so that you can ask for more or less permission in the auth flow
   * Set this before you call authenticate() though!
   * @param string $scopes, ie: "https://www.googleapis.com/auth/buzz https://www.googleapis.com/auth/latitude https://www.googleapis.com/auth/moderator"
   */
  public function setScopes($scopes) {
    $this->scopes = $scopes;
  }

  private function discoverService($serviceName, $serviceURI) {
    $request = $this->io->makeRequest(new apiHttpRequest($serviceURI));
    if ($request->getResponseHttpCode() != 200) {
      throw new apiException("Could not fetch discovery document for $service, http code: " . $request->getResponseHttpCode() . ", response body: " . $request->getResponseBody());
    }
    $discoveryResponse = $request->getResponseBody();
    $discoveryDocument = json_decode($discoveryResponse, true);
    if ($discoveryDocument == NULL) {
      throw new apiException("Invalid json returned for $service");
    }
    return new apiService($serviceName, $discoveryDocument, $this->io);
  }

  public function registerTypeHandler($type, $handlerClass) {
    global $apiTypeHandlers;
    $apiTypeHandlers[$type] = $handlerClass;
  }

  public function getIo() {
    return $this->io;
  }

}
