<?php

namespace App\Events;

use App\Models\User;
use App\Models\Expense;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class TransactionCreated
{
    use Dispatchable, SerializesModels;

    public $transaction;
    public $user;

    public function __construct(Expense $transaction, User $user)
    {
        $this->transaction = $transaction;
        $this->user = $user;
    }
}
