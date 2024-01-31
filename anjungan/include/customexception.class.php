<?php
require_once("iexception.class.php");

abstract class CustomException extends Exception implements IException
{
    protected $message = 'Unknown exception';     // Exception message
    private   $string;                            // Unknown
    protected $code    = 0;                       // User-defined exception code
    protected string $file = '';                              // Source filename of exception
    protected int  $line;                               // Source line of exception
    private   $trace;                             // Unknown

    public function __construct($message = null, $code = 0)
    {
        if (!$message) {
            throw new $this('Unknown '. static::class);
        }
        parent::__construct($message, $code);
    }
   
    public function __toString(): string
    {
        return static::class . " '{$this->message}' in ({$this->line})<br>"
                                . "{$this->getTraceAsString()}";
    }
}
?>