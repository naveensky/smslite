<?php
/**
 * Created by JetBrains PhpStorm.
 * User: hitanshu
 * Date: 22/3/13
 * Time: 4:38 PM
 * To change this template use File | Settings | File Templates.
 */

class AdminRepository
{
    private $smsRepo;

    public function __construct()
    {
        $this->smsRepo = new SMSRepository();
    }

    public function createTransaction($userId, $orderId, $amount, $discount, $remarks, $smsCredits, $schoolId, $ip)
    {
        $grossAmount = $amount - ($discount / 100 * $amount);
        $transaction = new Transaction();
        $transaction->orderId = $orderId;
        $transaction->amount = $amount;
        $transaction->discount = $discount;
        $transaction->grossAmount = $grossAmount;
        $transaction->remarks = $remarks;
        $transaction->smsCredits = $smsCredits;
        $transaction->schoolId = $schoolId;
        $transaction->userId = $userId;
        $transaction->ip = $ip;
        try {
            DB::connection()->pdo->beginTransaction();
            $transaction->save();
            $this->smsRepo->addCredits($smsCredits, $schoolId);
            DB::connection()->pdo->commit();
        } catch (PDOException $e) {
            //rollback if any error while bulk insertion
            DB::connection()->pdo->rollBack();
            Log::exception($e);
            return false;
        }
        catch (Exception $e) {
            Log::exception($e);
            return false;
        }
        return $transaction;
    }
}