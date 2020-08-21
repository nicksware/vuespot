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

class Error extends \Error
{ // implements \Throwable {
    protected $type;

    public function __constructor(\Error $e)
    {
        $this->code = $e->code;
        $this->message = $e->message;
        $this->file = $e->file;
        $this->line = $e->line;
    }

    final public function getType(): string
    {
        return $this->type;
    }

    final public function getMessageSummary(): string
    {
        $s = substr(
            $this->message,
            strpos($this->message, ':', 0) + 2,
            (int) strpos($this->message, PHP_EOL)
        );
        return explode(" in ", $s)[0];
    }

    public static function withNr($code, $message, $file, $line): Error
    {
        $instance = new self();
        $instance->set(Error::codeToString($code), $code, $message, $file, $line);
        return $instance;
    }

    public static function withType($type, $code, $message, $file, $line): Error
    {
        $instance = new self();
        $instance->set($type, $code, $message, $file, $line);
        return $instance;
    }

    private function set($type, $code, $message, $file, $line)
    {
        $this->type = $type;
        $this->code = $code;
        $this->message = $message;
        $this->file = $file;
        $this->line = $line;
    }

    private static function codeToString($errno = null): string
    {
        $types = array(
            E_ERROR => 'Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parsing Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Runtime Notice',
            E_RECOVERABLE_ERROR => 'Catchable Fatal Error',
            UPLOAD_ERR_OK => 'There is no error, the file uploaded with success',
            UPLOAD_ERR_INI_SIZE => 'File to big for server',
            UPLOAD_ERR_FORM_SIZE => 'File to big for form',
            UPLOAD_ERR_PARTIAL => 'File partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
        );
        return $types[$errno] ?? 'Unknown error';
    }
}
