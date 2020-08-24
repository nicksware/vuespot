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

class ValidateGnupgKeys
{
    private $ok = true;
    private $expected = [
        "disabled" => false,
        "expired" => false,
        "revoked" => false,
        "can_sign" => true,
        "can_encrypt" => true,
        "invalid" => false
    ];

    public function vallid(): bool
    {
        return $this->ok;
    }

    public function handle($v, $k)
    {
        if (isset($this->expected[$k])) $this->ok = $this->ok == true && $this->expected[$k] == $v;
    }
}
