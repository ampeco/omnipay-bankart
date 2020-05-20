<?php


namespace Ampeco\OmnipayBankart\Message;

use PaymentGateway\Client\Transaction\Refund;

class RefundRequest extends CaptureRequest
{
    protected $transactionClass = Refund::class;
    protected $transactionName = 'refund';
}
