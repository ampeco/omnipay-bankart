<?php


namespace Ampeco\OmnipayBankart\Message;

use PaymentGateway\Client\Transaction\Capture;
use PaymentGateway\Client\Transaction\VoidTransaction;

class VoidRequest extends Request
{
    protected $transactionClass = VoidTransaction::class;
    protected $transactionName = 'void';

    public function getData()
    {
        return [
            'transaction_id' => $this->getTransactionId(),
            'reference_transaction_id' => $this->getTransactionReference(),
        ];
    }
}
