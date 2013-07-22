<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | File and Directory Modes
  |--------------------------------------------------------------------------
  |
  | These prefs are used when checking and setting modes when working
  | with the file system.  The defaults are fine on servers with proper
  | security, but you may wish (or even need) to change the values in
  | certain environments (Apache running a separate process for each
  | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
  | always be used to set the mode correctly.
  |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
  |--------------------------------------------------------------------------
  | File Stream Modes
  |--------------------------------------------------------------------------
  |
  | These modes are used when working with fopen()/popen()
  |
 */

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');


/*
 * Session KEys
 */

define('SESSION_IS_LOGGED_IN', 'is_logged_in');
define('SESSION_LOGGED_IN_USER_ID', 'logged_in_userid');
define('SESSION_LOGGED_IN_USERNAME', 'logged_in_username');

define('TRANSACTION_PAGINATION_MAX_VALUE', 1000);
define('TRANSACTION_PAGINATION_VALUE', 10);

define ('SORT_BY_DATE_DEC' , 'date-dec');
define ('SORT_BY_DATE_INC' , 'date-inc');
define ('SORT_BY_AMOUNT_DEC' , 'amount-dec');
define ('SORT_BY_AMOUNT_INC' , 'amount-inc');


/* End of file constants.php */
/* Location: ./application/config/constants.php */