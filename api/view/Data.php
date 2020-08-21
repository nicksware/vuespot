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

class Data
{
    private $value = [];
    private $key = 'list';

    public function __construct(array $array, string $name = 'list')
    {
        $this->value = $array;
        $this->key = $name;
    }

    public function __destruct()
    {
        header('Content-Type: application/json');
        if (count($this->value) == 1 && !empty($this->value[0])) die(json_encode((object) [
            $this->key => $this->value[0]
        ]));
        else die(json_encode((object) [
            $this->key => $this->value
        ]));
    }
}
