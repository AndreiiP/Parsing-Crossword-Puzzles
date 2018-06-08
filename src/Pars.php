<?php
require 'vendor/autoload.php';
use React\ChildProcess\Process;


class Pars
{
    function parsing(){

        $url = 'https://www.kreuzwort-raetsel.net/a';
        $file = file_get_contents($url);
        $doc = phpQuery::newDocument($file);
        $lastPage = $doc->find('.Text:first')->find('a')->text();
        $lastPage = mb_strtolower($lastPage);
        $arr = explode(PHP_EOL, $lastPage);
        array_pop($arr);

        $loop = React\EventLoop\Factory::create();

        foreach ($arr as $url){

            $process = new Process( "php one.php $url");
            $process->start($loop);

            $process->stdout->on('data', function ($chunk) {
                echo $chunk;
            });

            $process->on('exit', function($exitCode, $termSignal) {
                echo 'Process exited with code ' . $exitCode . PHP_EOL;
            });
        }
        $loop->run();
    }
}

