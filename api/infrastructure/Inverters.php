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

class Inverters extends DatabaseConnection
{
    public function get(): array
    {
        $r = array();
        $q = "SELECT * FROM Inverters;";
        if ($d = $this->mysqli->query($q)) {
            while ($obj = $d->fetch_object()) {
                $r[] = $obj;
            }
        }
        return $r;
    }
}
