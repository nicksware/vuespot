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

namespace app;

ob_start([
    new class
    {
        public function handle(string $buffer): string
        {
            $buffer = str_replace("]{", "],{", $buffer);
            $buffer = str_replace("}[", "},[", $buffer);
            $buffer = str_replace("}{", "},{", $buffer);
            $buffer = '[' . str_replace("][", "],[", $buffer) . ']';
            return $this->merge($buffer);
        }

        private function merge(string $buffer): string
        {
            $res = [];
            $arr = json_decode($buffer);
            if (json_last_error() == JSON_ERROR_NONE) foreach ($arr as $e) {
                $res = array_merge($res, (array) $e);
            }
            return json_encode($res);
        }
    }, 'handle'
]);
