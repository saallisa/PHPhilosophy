<?php

/*
 * Configuration of database
 *
 * @author      Lisa Saalfrank <lisa.saalfrank@web.de>
 * @copyright   2015-2016 PHPhilosophy
 * @license	    http://opensource.org/licenses/MIT MIT License
 */
$database = [];

/* 
 * PLEASE CHANGE THESE VALUES ACCORDINGLY!
 */
 
// The name of your database server
$database['dbhost']   = 'localhost';
// The name of your database user
$database['dbuser']   = 'root';
// The users password, if set, otherwise left empty
$database['dbpass']   = '';
// The name of the database
$database['dbname']   = 'database';

/**
 * ADDITIONAL CONFIGURATION VALUES
 */
 
// The default character set
$database['dbchar']   = 'utf8';