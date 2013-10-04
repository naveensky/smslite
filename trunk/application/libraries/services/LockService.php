<?php
/**
 * Created by JetBrains PhpStorm.
 * User: keshav
 * Date: 26/9/13
 * Time: 7:23 PM
 * To change this template use File | Settings | File Templates.
 */

class LockService
{
    private $lockRepo;

    public function __construct()
    {
        $this->lockRepo = new LockRepository();
    }

    public function lockStatus($name)
    {
        try {
            $lock = $this->lockRepo->getLock($name);
            if (empty($lock))
                $lock = $this->addLock($name);
            return $lock->isRunning;
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function addLock($name)
    {
        try {
            return $this->lockRepo->addLock($name);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function getLock($name)
    {
        try {
            return $this->freeLock($name, true);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function freeLock($name, $isRunning)
    {
        $lockRepo = $this->lockRepo;
        try {
            return DB::transaction(function () use ($name, $isRunning, $lockRepo) {
                return $lockRepo->freeLock($name, $isRunning);
            });

        } catch (Exception $e) {
            Log::error($e);
            throw $e;
        }
    }
}