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

namespace app;

use \model\AppConfig;

require_once 'Error.php';
require_once 'ErrorCommand.php';

abstract class ErrorHandler
{
    public abstract function handle(int $errno = 0, string $errstr = '', string $errfile = '', int $errline = 0);

    final protected function view(Error $e)
    {
        $command = ErrorCommand::build();
        return $command[PHP_SAPI][AppConfig::debug()] ?? $command['api'][AppConfig::debug()]($e);
    }
}

register_shutdown_function([
    new class extends ErrorHandler
    {
        public function handle(int $errno = 0, string $errstr = '', string $errfile = '', int $errline = 0)
        {
            $error = error_get_last();
            if ($error !== NULL) {
                die($this->view(Error::withType(
                    'Critical Error',
                    $error["type"],
                    $error["message"],
                    $error["file"],
                    $error["line"]
                )));
            }
        }
    }, 'handle'
]);

set_error_handler([
    new class extends ErrorHandler
    {
        private $list;

        public function __construct()
        {
            $this->list = array();
        }

        public function __destruct()
        {
            if (PHP_SAPI == "cli") foreach ($this->list as $k => $v) {
                echo $v . "\n";
            }
            else {
                $res = [];
                foreach ($this->list as $k => $v) {
                    array_push($res, json_decode($v));
                }
                if (!empty($res)) die(json_encode((object) [
                    'errors' => $res
                ]));
            }
        }

        public function handle(int $errno = 0, string $errstr = '', string $errfile = '', int $errline = 0)
        {
            array_push($this->list, $this->view(Error::withNr(
                $errno,
                $errstr,
                $errfile,
                $errline
            )));

            switch ($errno) {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                case E_STRICT:
                case E_RECOVERABLE_ERROR:
                    exit(1);
                    break;
            }

            return true; // Don't execute PHP internal error handler
        }
    }, 'handle'
], E_ALL);

error_reporting(0); // error_reporting(-1); // hide all errors
