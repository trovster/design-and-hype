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
 * Wrapper for the (experimental!) JSON-RPC protocol, for production use regular REST calls instead
 *
 * @author Chris Chabot <chabotc@google.com>
 */
class apiBatch {

  /**
   * Execute one or multiple Google API requests, takes one or multiple requests as param
   * Example usage:
   *   $ret = apiBatch::execute(
   *     $apiClient->activities->list(array('@public', '@me'), 'listActivitiesKey'),
   *     $apiClient->people->get(array('userId' => '@me'), 'getPeopleKey')
   *   );
   *   print_r($ret['getPeopleKey']);
   */
  static public function execute( /* polymorphic */) {
    $requests = func_get_args();
    return apiRPC::execute($requests);
  }

}
