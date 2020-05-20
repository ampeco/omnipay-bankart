<?php


namespace Ampeco\OmnipayBankart\Message;

use PaymentGateway\Client\Transaction\Capture;

class CaptureRequest extends Request
{
    protected $transactionClass = Capture::class;
    protected $transactionName = 'capture';

    public function getData()
    {
        return [
            'transaction_id' => $this->getTransactionId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'reference_transaction_id' => $this->getTransactionReference(),
        ];
    }
}
