<?php


namespace Ampeco\OmnipayBankart\Message;


use PaymentGateway\Client\Transaction\Deregister;

class DeleteCardRequest extends Request
{
    protected $transactionClass = Deregister::class;
    protected $transactionName = 'deregister';

    public function getData()
    {
        return [
            'transaction_id' => $this->getTransactionId(),
            'reference_transaction_id' => $this->getCardReference(),
        ];
    }
}
