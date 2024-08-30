<?php

namespace App\Controllers;

use App\Models\Transaction;
use App\Helpers\ViewHelper;

class TransactionsController
{
    private $transactionModel;
    private $viewHelper;

    public function __construct(Transaction $transactionModel, ViewHelper $viewHelper)
    {
        $this->transactionModel = $transactionModel;
        $this->viewHelper = $viewHelper;
    }

    public function listTransactions()
{
    $transactions = $this->transactionModel->getAllTransactions();
    $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

    if ($isApiRequest) {
        // API response
        $this->viewHelper->respondJson(['transactions' => $transactions]);
    } else {
        // Web response
        $this->viewHelper->render('transactions/list', ['transactions' => $transactions]);
    }
}

public function viewTransaction($id)
{
    $transaction = $this->transactionModel->getTransactionById($id);
    $isApiRequest = strpos($_SERVER['REQUEST_URI'], '/vms/api') === 0;

    if ($transaction) {
        if ($isApiRequest) {
            // API response
            $this->viewHelper->respondJson(['transaction' => $transaction]);
        } else {
            // Web response
            $this->viewHelper->render('transactions/view', ['transaction' => $transaction]);
        }
    } else {
        if ($isApiRequest) {
            // API response
            http_response_code(404);
            $this->viewHelper->respondJson(['message' => 'Transaction not found']);
        } else {
            // Web response
            http_response_code(404);
            echo 'Transaction not found';
        }
    }
}

}
