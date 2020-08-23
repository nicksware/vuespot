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

class DatabaseConfig extends Config
{
    private static $instance = null;

    protected static function config()
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseConfig();
        }
        return self::$instance->vallid('database') ? self::$instance->config->database : (object) [];
    }

    public static function host(): string
    {
        return property_exists(DatabaseConfig::config(), 'host') ? strval(DatabaseConfig::config()->host) : "localhost";
    }

    public static function name(): string
    {
        return property_exists(DatabaseConfig::config(), 'name') ? strval(DatabaseConfig::config()->name) : "test";
    }

    public static function user(): string
    {
        return property_exists(DatabaseConfig::config(), 'user') ? strval(DatabaseConfig::config()->user) : "root";
    }

    public static function pass(): string
    {
        return property_exists(DatabaseConfig::config(), 'password') ? strval(DatabaseConfig::config()->password) : "";
    }
}
