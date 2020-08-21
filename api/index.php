<?php

/*
 * This work is licensed under the Creative Commons Attribution-NonCommercial-ShareAlike 3.0 Unported License. To view a copy of this license, visit http://creativecommons.org/licenses/by-nc-sa/3.0/ or send a letter to Creative Commons, PO Box 1866, Mountain View, CA 94042, USA.
 */

/**
 * @license http://creativecommons.org/licenses/by-nc-sa/3.0/  cc-by-nc-sa 3.0
 * @author Nick <noobping@users.noreply.github.com>
 * @since 7.3
 */

declare(strict_types=1);
error_reporting(0); // hide all errors

use model\AppConfig;

header('Access-Control-Allow-Methods: GET');
header("Access-Control-Allow-Headers: X-Requested-With");
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

date_default_timezone_set('UTC');

$version = explode('.', phpversion());
if (!($version[0] >= 7 && $version[1] >= 3 && $version[2] >= 0)) {
    header('Content-Type: application/json');
    http_response_code(500);
    die(json_encode((object) [
        'error' => [
            'type' => 'Critical Error',
            'message' => 'Outdated software. Please, update'
        ],
    ]));
}

if (PHP_SAPI != "cli") require_once 'app/buffer.php'; // output buffer for multiple json results

spl_autoload_extensions(".php");
spl_autoload_register();
require_once 'app/Loader.php';

if (!AppConfig::debug()) {
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('X-Content-Type-Options: nosniff');
}

if (AppConfig::cors()) header("Access-Control-Allow-Origin: *");

require_once 'app/ErrorHandler.php'; // custom error handler

class App
{
    private $controller;
    private $param;

    public function __construct(array $arg = [])
    {
        $this->param = array_merge((isset($_GET) ? $_GET : []), $arg, (isset($_POST) ? $_POST : []));
        $class = "\\view\\NotFound";

        if (!empty($this->param)) $tmp = htmlspecialchars(stripslashes(nl2br(strip_tags(trim(array_values($this->param)[0])))));
        if (!empty($tmp) && is_string($tmp) && class_exists("\\endpoint\\" . ucfirst(explode('/', $tmp)[0]))) {
            array_shift($this->param);
            $arr = explode('/', $tmp);
            $class = "\\endpoint\\" . ucfirst($arr[0]);
            $this->param = array_merge((array)$this->param, (array)$arr);
        }

        $this->controller = new $class(); // specify the controller (endpoint)
    }

    public function __destruct()
    {
        call_user_func_array([$this->controller, $this->method($this->controller)], $this->param); // handle the endpoint dynamically
    }

    /**
     * get the request method for the controller (endpoint) class
     */
    private function method()
    {
        $method = !empty($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'cli'; // request method is the class method. 
        return method_exists($this->controller, $method) ? $method : new \view\NotFound('controller method', [$this->controller, $method]);
    }
};

$arg = [];
if (isset($argv)) {
    $tmp = $argv;
    array_shift($tmp);
    $arg = array_values($tmp);
}
new App($arg);

if (PHP_SAPI != "cli") ob_end_flush(); // end json buffer
