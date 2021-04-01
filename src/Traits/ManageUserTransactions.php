<?php
namespace Devinweb\LaravelHyperpay\Traits;

use Devinweb\LaravelHyperpay\Models\Transaction;

trait ManageUserTransactions
{

    /**
     * Get the transaction instance by User ID.
     *
     * @param  int  $user_id
     * @return \Models\Transaction|null
     */
    public static function findPending($userId)
    {
        if ($userId === null) {
            return;
        }

        return Transaction::where('user_id', $userId)->where('status', '!=', 'failed')->first();
    }
}
