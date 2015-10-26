<?php
/**
 * Created by PhpStorm.
 * User: Jenner
 * Date: 2015/8/21
 * Time: 14:30
 */

namespace Jenner\SimpleFork\Lock;


class FileLock implements LockInterface
{
    /**
     * @var string lock file
     */
    protected $file;

    /**
     * @var resource
     */
    protected $fp;

    /**
     * @var bool
     */
    protected $locked = false;

    /**
     * create a file lock instance
     *
     * @param $file
     * @return FileLock
     */
    public static function create($file)
    {
        return new FileLock($file);
    }

    /**
     * @param $file
     */
    private function __construct($file)
    {
        if (!file_exists($file) || !is_readable($file)) {
            throw new \RuntimeException("{$file} is not exists or not readable");
        }
        $this->fp = fopen($file, "r+");
        if (!is_resource($this->fp)) {
            throw new \RuntimeException("open {$file} failed");
        }
    }

    /**
     * get a lock
     *
     * @return mixed
     */
    public function acquire()
    {
        if ($this->locked) {
            throw new \RuntimeException("already lock by yourself");
        }

        $locked = flock($this->fp, LOCK_EX);
        if (!$locked) {
            return false;
        }
        $this->locked = true;

        return true;
    }

    /**
     * release lock
     *
     * @return mixed
     */
    public function release()
    {
        if (!$this->locked) {
            throw new \RuntimeException("release a non lock");
        }

        $unlock = flock($this->fp, LOCK_UN);
        if (!$unlock) {
            return false;
        }
        $this->locked = false;

        return true;
    }

    /**
     * is locked
     *
     * @return mixed
     */
    public function isLocked()
    {
        return $this->locked === true ? true : false;
    }

    /**
     *
     */
    public function __destory()
    {
        if ($this->locked) {
            $this->release();
        }
    }
}