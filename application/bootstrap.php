<?php defined('SYSPATH') or die('No direct script access.');

// -- Environment setup --------------------------------------------------------

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('Europe/Amsterdam');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang('en-us');

Cookie::$salt = 'whatever';

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset($_SERVER['KOHANA_ENV'])) {
	Kohana::$environment = constant('Kohana::'.strtoupper($_SERVER['KOHANA_ENV']));
} else {
	Kohana::$environment = Kohana::PRODUCTION;
}

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
	'index_file' => false,
));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Config_File);
Kohana::$base_url = Kohana::$config->load('application')->get('base_url');

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Log_File(APPPATH.'logs'));

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
$modules = array(
	'cache'      => MODPATH . 'cache',      // Caching with multiple backends
	'image'      => MODPATH . 'image',      // Image manipulation
	'userguide'  => MODPATH . 'userguide',  // User guide and API documentation
);
// Load current templates from application config
foreach (Kohana::$config->load('application.templates') as $template) {
	$modules[$template] = MODPATH . $template;
}
Kohana::modules($modules);

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('media', 'media(/<file>)', array('file' => '.+'))
	->defaults(array('controller' => 'media'));

Route::set('admin', 'admin(/<action>(/<key>))')
	->defaults(array('controller' => 'admin'));

Route::set('error', 'error/<code>(/<message>)', array(
	'code' => '[0-9]+',
	'message' => '.+',
))->defaults(array('controller' => 'gallery', 'action' => 'error'));

Route::set('gallery', '(<dir>)', array('dir' => '.+'))
	->defaults(array('controller' => 'gallery'));
