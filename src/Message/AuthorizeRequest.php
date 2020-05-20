<?php


namespace Ampeco\OmnipayBankart\Message;


use PaymentGateway\Client\Transaction\Preauthorize;

class AuthorizeRequest extends Request
{

    protected $transactionClass = Preauthorize::class;
    protected $transactionName = 'preauthorize';

    public function setCustomer($customer){
        return $this->setParameter('Customer', $customer);
    }

    public function getCustomer(){
        return $this->getParameter('Customer');
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
        ];

        if ($this->getCardReference()){
            $res['reference_transaction_id'] = $this->getCardReference();
        }
        return $res;
    }

}
