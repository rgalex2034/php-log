<?php

require_once __DIR__."/../vendor/autoload.php";

use PHPUnit\Framework\TestCase;
use Nyugodt\Log\Syslog;

class SyslogTest extends TestCase{

    /**
     * @test
     */
    public function info(){
        $syslog = new Syslog("[Nyugot]");
        $syslog->info("Test info logging");
        $this->assertTrue(true);
    }

}
