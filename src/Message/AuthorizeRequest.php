<?php


namespace Ampeco\OmnipayBankart\Message;


use PaymentGateway\Client\Transaction\Preauthorize;

class AuthorizeRequest extends Request
{

    protected $transactionClass = Preauthorize::class;
    protected $transactionName = 'preauthorize';

    public function setCustomer($customer)
    {
        return $this->setParameter('Customer', $customer);
    }

    public function getCustomer()
    {
        return $this->getParameter('Customer');
    }

    public function setTransactionIndicator($value)
    {
        return $this->setParameter('TransactionIndicator', $value);
    }

    public function getTransactionIndicator()
    {
        return $this->getParameter('TransactionIndicator');
    }

    public function getData()
    {
        $res = [
            'transaction_id' => $this->getTransactionId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'description' => $this->getDescription(),
            'success_url' => $this->getReturnUrl(),
            'cancel_url' => $this->getReturnUrl(),
            'error_url' => $this->getReturnUrl(),
            'callback_url' => $this->getNotifyUrl(),
            'customer' => $this->getCustomer(),
            'transaction_indicator' => $this->getTransactionIndicator() ?: Preauthorize::TRANSACTION_INDICATOR_SINGLE,
            'extra_data' => [
                '3ds:authenticationIndicator' => $this->get3dsAuthenticationIndicator(),
                '3ds:recurringFrequency' => $this->get3dsRecurringFrequency(),
                '3ds:challengeIndicator' => $this->get3dsChallengeIndicator(),
            ],
        ];

        if ($this->getCardReference()) {
            $res['reference_transaction_id'] = $this->getCardReference();
        }
        return $res;
    }

}
