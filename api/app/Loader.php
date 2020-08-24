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

class IndexFilter extends \RecursiveFilterIterator
{
    public function accept()
    {
        return $this->current()->isFile() && $this->current()->getFilename() == 'index.php';
    }
}

abstract class Loader
{
    public abstract function autoload(string $class);

    final protected function find_index_path($path = './', $max = 3, $counter = 0)
    {
        $counter++;
        return realpath($counter > $max || iterator_count(new IndexFilter(new \RecursiveDirectoryIterator($path))) > 0 ? $path : $this->find_index_path($path . '../', $max, $counter));
    }

    final protected function class_path($class): string
    {
        return str_replace("\\", DIRECTORY_SEPARATOR, $class);
    }
}

\spl_autoload_register([
    new class extends Loader
    {
        private $root;

        public function __construct()
        {
            $this->root = getcwd();
        }

        public function autoload(string $class)
        {
            $file = DIRECTORY_SEPARATOR . $this->class_path($class) . '.php';
            if (file_exists($this->root . $file)) {
                require_once $this->root . $file;
            }
        }
    }, 'autoload'
]);
