<?php

namespace Resources\Utils;

use Monolog\Logger as Log;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Formatter\LineFormatter;
use Monolog\LogRecord;

class CustomLineFormatter extends LineFormatter {
    public function format(LogRecord $record): string {
        $output = parent::format($record);
        $output = rtrim($output, "\r\n") . "\n\n";
        return $output;
    }
}


class Logger
{
    public static function emergency($message = false)
    {
        global $M;
        $message = $message ? $message : $M->Config->message->UNAVAILABLE;
        self::action($message, Level::Emergency);
    }

    public static function warning($message = false)
    {
        global $M;
        $message = $message ? $message : $M->Config->message->UNAVAILABLE;
        self::action($message, Level::Warning);
    }

    public static function action($message = false, $level = Level::Debug) {
        global $M;
        $message = $message ?: $M->Config->message->UNAVAILABLE;
        $datetime = date("Y-m-d");
        $log = new Log("Kernel");
    
        try {
            $logDirectory = ROOT_FOLDER . "logs/errors";
            if (!file_exists($logDirectory)) {
                mkdir($logDirectory, 0755, true);
            }
    
            $handler = new StreamHandler(ROOT_FOLDER."logs/errors/$datetime.log", $level);
            $handler->setFormatter(new CustomLineFormatter(null, null, true, true));
    
            $log->pushHandler($handler);
    
            $log->warning($message);
        } catch (\Exception $e) {
            echo "Erro ao configurar o logger: " . $e->getMessage();
        }
    }
}