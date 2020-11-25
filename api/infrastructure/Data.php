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

use model\AppConfig;

class Data extends DatabaseConnection
{
    public function day(string $serial, int $time = 0): array
    {
        $r = array();
        $q = "SELECT 
            FROM_UNIXTIME(TimeStamp) AS TimeStamp, 
            TotalYield, Power
            FROM DayData 
            WHERE Serial LIKE '$serial' 
            AND DATE(FROM_UNIXTIME(TimeStamp)) LIKE DATE(FROM_UNIXTIME($time)) 
            ORDER By TimeStamp DESC;";
        if ($d = $this->mysqli->query($q)) {
            while ($obj = $d->fetch_object()) {
                $r[] = $obj;
            }
        }
        return $r;
    }

    public function month(string $serial, int $time = 0): array
    {
        $r = array();
        $q =
            "SELECT 
            FROM_UNIXTIME(TimeStamp) AS TimeStamp, 
            TotalYield, DayYield
            FROM MonthData 
            WHERE Serial LIKE '$serial' 
            AND YEAR(FROM_UNIXTIME(TimeStamp)) LIKE YEAR(FROM_UNIXTIME($time)) 
            AND MONTH(FROM_UNIXTIME(TimeStamp)) LIKE MONTH(FROM_UNIXTIME($time)) 
            ORDER By TimeStamp DESC;";
        if ($d = $this->mysqli->query($q)) {
            while ($obj = $d->fetch_object()) {
                $r[] = $obj;
            }
        }
        return $r;
    }

    public function year(string $serial): array
    {
        $r = array();
        $q =
            "SELECT 
            YEAR(FROM_UNIXTIME(TimeStamp)) AS 'Year', 
            SUM(TotalYield) AS 'TotalYield',  
            SUM(DayYield) AS 'DayYield' 
            FROM MonthData 
            WHERE Serial LIKE '$serial' 
            GROUP BY YEAR(FROM_UNIXTIME(TimeStamp)) 
            ORDER By TimeStamp DESC;";
        if ($d = $this->mysqli->query($q)) {
            while ($obj = $d->fetch_object()) {
                $r[] = $obj;
            }
        }
        return $r;
    }

    public function event(string $serial, int $page = 0): array
    {
        $page = max(0, $page * 10);
        $r = array();
        $q =
            "SELECT 
            FROM_UNIXTIME(TimeStamp) AS 'TimeStamp', 
            EventCode, EventType, Category, EventGroup, Tag, OldValue, NewValue
            FROM EventData 
            WHERE Serial LIKE '$serial' 
            ORDER By TimeStamp DESC
            LIMIT $page, " . AppConfig::max() . ";";
        if ($d = $this->mysqli->query($q)) {
            while ($obj = $d->fetch_object()) {
                $r[] = $obj;
            }
        }
        return $r;
    }
}
