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

class AppConfig extends Config
{
    private static $instance = null;

    protected static function config()
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseConfig();
        }
        return self::$instance->vallid() ? self::$instance->config : (object) [
            "serial" => "",
            "debug" => AppConfig::auto_detect()
        ];
    }

    public static function serial(): string
    {
        return property_exists(AppConfig::config(), 'serial') ? strval(AppConfig::config()->serial) : "";
    }

    public static function max(): int
    {
        return property_exists(AppConfig::config(), 'maxItemsInPage') ? AppConfig::config()->maxItemsInPage : 5;
    }

    public static function cors(): bool
    {
        return property_exists(AppConfig::config(), 'crossDomain') ? AppConfig::config()->crossDomain : false;
    }

    public static function debug(): bool
    {
        return property_exists(AppConfig::config(), 'debug') ? AppConfig::config()->debug : AppConfig::auto_detect();
    }

    private static function auto_detect(): bool
    {
        return empty($_SERVER['SERVER_NAME']) || $_SERVER['SERVER_NAME'] == '127.0.0.1';
    }
}
