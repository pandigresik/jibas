<?php
class Logger
{
    private string $logFile = "";
    private $file;

    public function __construct()
    {
        $logPath = realpath(__DIR__) . "/../log";
        if (!file_exists($logPath))
            mkdir($logPath);

        $this->logFile = realpath(__DIR__) . "/../log/debugger.log";

        $this->file = fopen($this->logFile, "a");
        fwrite($this->file, "-------------------------------------------\r\n");
    }

    public function ClearLog()
    {
        ftruncate($this->file, 0);
    }

    public function Log($message)
    {
        fwrite($this->file, date('H:i:s') . " " . $message . "\r\n");
    }

    public function LogError($message)
    {
        fwrite($this->file, date('H:i:s') . " ERROR: " . $message . "\r\n");
    }

    public function LogNoTime($message)
    {
        fwrite($this->file, $message . "\r\n");
    }

    public function Close()
    {
        fclose($this->file);
    }

    public static function LogOnce($message)
    {
        $log = new Logger();
        $log->Log($message);
        $log->Close();
    }

    public static function LogErrorOnce($exception, $code)
    {
        $log = new Logger();
        $log->Log($exception->getMessage() . " /$code");

        $stackTrace = $exception->getTrace();
        foreach ($stackTrace as $trace)

        {
            if (isset($trace['file']) && isset($trace['line']) && isset($trace['function']))
            {
                $st = "In " . $trace['file'] . " on line " . $trace['line'] . ", calling function: " . $trace['function'];
                $log->LogNoTime($st);
            }
        }

        $log->Close();
    }

    public static function LogDataOnce($message, $data, $exception)
    {
        $log = new Logger();
        $log->Log("---------------------");
        $log->LogNoTime($message);
        $log->LogNoTime($data);
        $log->LogNoTime($exception->getMessage());
        $log->Close();
    }
}
?>