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

class Favorites
{
    public static function get(): array
    {
        $path = getcwd() . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR;
        if (file_exists($path . 'favorites.json')) {
            $ip = empty($_SERVER['SERVER_ADDR']) ? $_SERVER['REMOTE_ADDR'] : $_SERVER['SERVER_ADDR'];
            $file = file_get_contents($path . 'favorites.json');
            return (array) json_decode(str_replace('localhost', $ip, $file));
        } else return [];
    }
}
