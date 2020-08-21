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

namespace model;

abstract class Config
{
    protected $config = null;

    protected function __construct()
    {
        $path = getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'config.json';
        if (file_exists($path)) {
            $json = file_get_contents($path);
            if (is_string($json) && !empty($json)) {
                $obj = json_decode($json);
                if (json_last_error() == JSON_ERROR_NONE) $this->config = $obj;
            }
        }
    }

    protected function vallid(string $property = ""): bool {
        return $this->config != null && (empty($property) ? true : property_exists($this->config, $property));
    }

    protected static abstract function config();
}
