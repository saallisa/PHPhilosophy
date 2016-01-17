<?php 

use Phphilosophy\Autoload\Autoload;
use Phphilosophy\Application\Config;

/*
 * Phphilosophy bootstrap file
 *
 * @author      Lisa Saalfrank <lisa.saalfrank@web.de>
 * @copyright   2015-2016 Lisa Saalfrank
 * @license	    http://opensource.org/licenses/MIT MIT License
 */

// Require the autoload and the config file
require __DIR__ . '/../framework/Autoload/Autoload.php';
require __DIR__ . '/../application/configs/application.php';
require __DIR__ . '/../application/configs/database.php';

// create the autoload instance
$autoload = new Autoload();

// Add namespace prefixes
$autoload->add('Phphilosophy', __DIR__ . '/../framework/');
$autoload->add($configs['app.name'] . '\\Model', __DIR__ . '/../application/models/');
$autoload->add($configs['app.name'] . '\\Controller', __DIR__ . '/../application/controllers/');
$autoload->add($configs['app.name'] . '\\Library', __DIR__ . '/../application/libraries/');

// register the autoloader
$autoload->register();

// Add config values
Config::set('database', $database);
Config::set('app.name', $configs['app.name']);