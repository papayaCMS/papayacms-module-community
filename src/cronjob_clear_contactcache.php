<?php
/**
* Cronjob module that deletes expired records from the contact path cache
*
* @copyright 2002-2009 by papaya Software GmbH - All rights reserved.
* @link http://www.papaya-cms.com/
* @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License, version 2
*
* You can redistribute and/or modify this script under the terms of the GNU General Public
* License (GPL) version 2, provided that the copyright and license notes, including these
* lines, remain unmodified. papaya is distributed in the hope that it will be useful, but
* WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
* FOR A PARTICULAR PURPOSE.
*
* @package Papaya-Modules
* @subpackage _Base-Community
* @version $Id: cronjob_clear_contactcache.php 39272 2014-02-19 16:27:18Z weinert $
*/

/**
* Cronjob module that deletes expired records from the contact path cache
*
* @package Papaya-Modules
* @subpackage _Base-Community
*/
class cronjob_clear_contactcache extends base_cronjob {
  /**
  * Execute the cronjob.
  *
  * The method itself is extremely short because it only calls
  * a utility method in the contact manager.
  *
  * @access public
  * @return integer 0
  */
  function execute() {
    $manager = contact_manager::getInstance();
    $manager->clearContactCache();
    // This always works, whether there are records to delete or not,
    // so we can return a hardcoded 0 (UNIX exit code for 'success')
    return 0;
  }

  /**
  * Check execution parameters
  *
  * As this module does not have any parameters,
  * it simply returns TRUE
  *
  * @access public
  * @return boolean TRUE
  */
  function checkExecParams() {
    return TRUE;
  }
}
