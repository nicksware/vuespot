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

namespace view;

use \model\AppConfig;

class NotFound
{
    private $name = "";
    private $args = null;

    public function __construct(string $name = "", $args = null)
    {
        if (!empty($name)) $this->name = ucwords(strtolower($name)) . ' ';
        $this->args = $args;
    }

    public function __destruct()
    {
        header('Content-Type: application/json');
        http_response_code(404);
        if (AppConfig::debug()) die(json_encode((object) [
            'error' => [
                'type' => 'User error',
                'message' => $this->name . 'Not Found',
                'code' => '404',
                'args' => $this->args,
                'trace' => debug_backtrace()
            ]
        ]));
        else die(json_encode((object) [
            'error' => [
                'type' => 'User error',
                'message' => 'Not Found'
            ]
        ]));
    }
}
