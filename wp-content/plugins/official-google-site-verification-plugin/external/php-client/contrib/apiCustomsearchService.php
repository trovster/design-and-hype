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

/**
 * The Customsearch service implementation
 *
 * Generated by http://code.google.com/p/google-api-php-client/
 * Generated from: https://www.googleapis.com/discovery/v0.2beta1/describe/customsearch/v1
 **/
class apiCustomsearchService {

  // Variables that the apiServiceResource implementation depends on
  private $serviceName = 'customsearch';
  private $version = 'v1';
  private $restBasePath = 'https://www.googleapis.com/customsearch/';
  private $rpcPath = 'https://www.googleapis.com/rpc';
  private $io;
  // apiServiceResource's that are used internally
  private $cse;

  /**
   * Constructs the internal service representations and does the auto-magic configuration required to drive them
   */
  public function __construct(apiClient $apiClient) {
    $apiClient->addService('customsearch', 'v1');
    $this->io = $apiClient->getIo();
    $this->cse = new apiServiceResource($this, $this->serviceName, 'cse', json_decode('{"methods":{"list":{"restPath":"v1","rpcMethod":"search.cse.list","httpMethod":"GET","parameters":{"cref":{"restParameterType":"query"},"cx":{"restParameterType":"query"},"lr":{"restParameterType":"query"},"num":{"restParameterType":"query"},"q":{"restParameterType":"query","required":true},"safe":{"restParameterType":"query"},"sort":{"restParameterType":"query"},"start":{"restParameterType":"query"}}}}}', true));
  }

  /**
   * Implementation of the search.cse.list method.
   * See: http://code.google.com/apis/buzz/v1/using_rest.html#search.cse.list
   *
   * @param $q required
   * @param $cref optional
   * @param $cx optional
   * @param $lr optional
   * @param $num optional
   * @param $safe optional
   * @param $sort optional
   * @param $start optional
   */
  public function listCse($q, $cref = null, $cx = null, $lr = null, $num = null, $safe = null, $sort = null, $start = null) {
    return $this->cse->__call('list', array(array('q' => $q, 'cref' => $cref, 'cx' => $cx, 'lr' => $lr, 'num' => $num, 'safe' => $safe, 'sort' => $sort, 'start' => $start)));
  }

  /**
   * @return the $io
   */
  public function getIo() {
    return $this->io;
  }

  /**
   * @return the $version
   */
  public function getVersion() {
    return $this->version;
  }

  /**
   * @return the $restBasePath
   */
  public function getRestBasePath() {
    return $this->restBasePath;
  }

  /**
   * @return the $rpcPath
   */
  public function getRpcPath() {
    return $this->rpcPath;
  }
}
