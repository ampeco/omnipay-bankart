<?php


namespace Ampeco\OmnipayBankart\Message;

use PaymentGateway\Client\Transaction\Debit;

class PurchaseRequest extends AuthorizeRequest
{
    protected $transactionClass = Debit::class;
    protected $transactionName = 'debit';
}
