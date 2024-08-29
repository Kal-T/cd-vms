<?php

namespace App\Controllers;

use App\Models\Transaction;
use App\Helpers\ViewHelper;

class TransactionsController
{
    private $transactionModel;

    public function __construct(Transaction $transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function listTransactions()
    {
        $transactions = $this->transactionModel->getAllTransactions();
        
        ViewHelper::render('transactions/list', ['transactions' => $transactions]);
    }

    public function viewTransaction($id)
    {
        $transaction = $this->transactionModel->getTransactionById($id);
        
        ViewHelper::render('transactions/view', ['transaction' => $transaction]);
    }

    public function createTransactionForm()
    {
        ViewHelper::render('transactions/create');
    }

    public function createTransaction()
    {
    }

    public function editTransactionForm($id)
    {
    }

    public function editTransaction($id)
    {
    }

    public function deleteTransaction($id)
    {
    }
}
