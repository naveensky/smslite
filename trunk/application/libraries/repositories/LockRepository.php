<?php
/**
 * Created by JetBrains PhpStorm.
 * User: keshav
 * Date: 26/9/13
 * Time: 7:23 PM
 * To change this template use File | Settings | File Templates.
 */

class LockRepository
{

    public function getLock($name)
    {
        try {
            $lock = SMSTask::where('name', '=', $name)->first();
            return $lock;
        } catch (Exception $e) {
            Log::exception($e);
            throw $e;
        }
    }

    public function addLock($name)
    {
        try {
            $task = new SMSTask();
            $task->name = $name;
            $task->isRunning = false;
            $task->save();
            return $task;
        } catch (Exception $e) {
            Log::exception($e);
            throw $e;
        }
    }

    public function freeLock($name, $isRunning)
    {
        try {
            $task = SMSTask::where('name', '=', $name)->first();
            if (is_null($task)) {
                Log::error('Task not found where name is ' . $name);
                throw new Exception ("Task not found where name is  . $name");
            }
            $task->isRunning = $isRunning;
            $task->save();
            return $task;
        } catch (Exception $e) {
            Log::exception($e);
            throw $e;
        }
    }
}