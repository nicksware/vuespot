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

interface IBuilder
{
    public static function build();
}

class ErrorCommand implements IBuilder
{
    public static function build()
    {
        $command['cli'][true] = function (Error $e) {
            return $e->getType() . ' in file ' . $e->getFile()
                . ' on line ' . $e->getLine() . ' ' . $e->getMessage();
        };
        $command['cli'][false] = function (Error $e) {
            return $e->getMessageSummary();
        };
        $command['api'][true] = function (Error $e) {
            header('Content-Type: application/json');
            http_response_code(AppConfig::debug() ? 200 : 500);
            return json_encode((object) [
                'error' => [
                    'type' => $e->getType(),
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTrace()
                ]
            ]);
        };
        $command['api'][false] = function (Error $e) {
            header('Content-Type: application/json');
            http_response_code(AppConfig::debug() ? 200 : 500);
            return json_encode((object) [
                'error' => [
                    'type' => $e->getType(),
                    'message' => $e->getMessageSummary()
                ]
            ]);
        };
        return $command;
    }
}
