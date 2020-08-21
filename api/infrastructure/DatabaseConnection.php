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

namespace infrastructure;

use \model\{
    AppConfig,
    DatabaseConfig
};
use \view\NotFound;

class DatabaseConnection
{
    protected $mysqli;

    public function __construct()
    {
        $this->mysqli = new \mysqli(DatabaseConfig::host(), DatabaseConfig::user(), DatabaseConfig::pass(), DatabaseConfig::name());

        if ($this->mysqli->connect_errno) { // chack the connection
            if (AppConfig::debug()) {
                http_response_code(200);
                die(json_encode((object) [
                    'error' => [
                        'type' => 'Database Error',
                        'message' => $this->mysqli->error
                    ],
                ]));
            } else new NotFound('Database');
        }
    }

    public function __destruct()
    {
        $this->mysqli->close();
    }

    protected function query($query)
    {
        return $this->mysqli->query($query);
    }

    /**
     * make the string value save to save it in the database
     */
    protected function s(string $s): string
    {
        return $this->mysqli->real_escape_string(htmlspecialchars(stripslashes(nl2br(strip_tags(trim($s))))));
    }
}
