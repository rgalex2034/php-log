<?php
namespace Nyugodt\Log;

use \Psr\Log\AbstractLogger;
use \Psr\Log\LogLevel;
use \Psr\Log\InvalidArgumentException;

class Syslog extends AbstractLogger{

    private $prefix;

    public function __construct(string $prefix = ""){
        $this->prefix = $prefix;
    }

    public function log($log_level, $message, $context = []){
        $message = (string)$message;
        $now = date("Y-m-d H:i:s");
        openlog("{$this->prefix}[$now][$log_level]", LOG_PID | LOG_PERROR, LOG_LOCAL0);
        syslog(self::getSyslogLevel($log_level), self::parseMessage($message, $context));
        closelog();
    }

    private static function parseMessage(string $message, $context = []): string{
        foreach($context as $key => $value){
            if((!is_array($value)
                || is_object($value) && method_exists($value, "__toString"))
                && preg_match("/^[a-zA-Z0-9_.]+$/", $key)
            ){
                $message = str_replace("{".$key."}", (string)$value, $message);
            }
        }

        return $message;
    }

    private static function getSyslogLevel($log_level){
        $level = null;
        switch($log_level){
            case LogLevel::ALERT:
                $level = LOG_ALERT;
                break;
            case LogLevel::CRITICAL:
                $level = LOG_CRIT;
                break;
            case LogLevel::DEBUG:
                $level = LOG_DEBUG;
                break;
            case LogLevel::EMERGENCY:
                $level = LOG_EMERG;
                break;
            case LogLevel::ERROR:
                $level = LOG_ERR;
                break;
            case LogLevel::INFO:
                $level = LOG_INFO;
                break;
            case LogLevel::NOTICE:
                $level = LOG_NOTICE;
                break;
            case LogLevel::WARNING:
                $level = LOG_WARNING;
                break;
        }

        if(!$level){
            throw new InvalidArgumentException("Invalid log level: $log_level");
        }

        return $level;
    }

}
