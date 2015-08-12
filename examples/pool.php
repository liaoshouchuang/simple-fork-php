<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/12
 * Time: 19:55
 */


require dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

class TestRunnable extends \Jenner\SimpleFork\Runnable{

    /**
     * ����ִ�����
     * @return mixed
     */
    public function run()
    {
        sleep(10);
        echo getmypid() . ':done' . PHP_EOL;
    }
}

$pool = new \Jenner\SimpleFork\Pool();
$pool->submit(new \Jenner\SimpleFork\Process(new TestRunnable()));
$pool->submit(new \Jenner\SimpleFork\Process(new TestRunnable()));
$pool->submit(new \Jenner\SimpleFork\Process(new TestRunnable()));

$pool->start();
$pool->wait();