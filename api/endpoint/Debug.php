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

namespace endpoint;

use view\Data;
use \model\AppConfig;

class Debug
{
    public function get()
    {
        header('Content-Type: application/json');
        die(json_encode((object) [
            "debug" => AppConfig::debug()
        ]));
    }

    public function cli()
    {
        header('Content-Type: application/text');
        die("Debug " . AppConfig::debug() ? "enabled" : "disabled");
    }
}
