<?php
namespace Nyugodt\Log;

use \Psr\Log\AbstractLogger;
use \Psr\Log\LoggerInterface;

class Aggregate extends AbstractLogger{

    private $loggers = [];

    public function __construct(LoggerInterface ...$loggers){
        $this->loggers = $loggers;
    }

    public function log($log_level, $message, $context = []){
        foreach($this->loggers as $logger){
            $logger->log($log_level, $message, $context);
        }
    }

    public function aggregate(LoggerInterface ...$loggers){
        $all_loggers = array_merge($this->loggers, $loggers);
        return new self(...$all_loggers);
    }
}
