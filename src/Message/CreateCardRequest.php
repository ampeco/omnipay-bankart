<?php


namespace Ampeco\OmnipayBankart\Message;


use PaymentGateway\Client\Transaction\Preauthorize;
use PaymentGateway\Client\Transaction\Register;

class CreateCardRequest extends Request
{

    protected $transactionClass = Register::class;
    protected $transactionName = 'register';

    public function setCustomer($customer){
        return $this->setParameter('Customer', $customer);
    }

    public function getCustomer(){
        return $this->getParameter('Customer');
    }

    public function get3dsAuthenticationIndicator()
    {
        return $this->getParameter('3dsAuthenticationIndicator');
    }

    public function set3dsAuthenticationIndicator($value)
    {
        return $this->setParameter('3dsAuthenticationIndicator', $value);
    }

    public function getData()
    {

        $res = [
            'transaction_id' => $this->getTransactionId(),
            'description' => $this->getDescription(),
            'success_url' => $this->getReturnUrl(),
            'cancel_url' => $this->getReturnUrl(),
            'error_url' => $this->getReturnUrl(),
            'callback_url' => $this->getNotifyUrl(),
            'customer' => $this->getCustomer(),
            'extra_data' => [
                '3ds:authenticationIndicator' => $this->get3DSAuthenticationIndicator(),
            ],
        ];

        if ($this->getAmount() && $this->getCurrency()){
            // Use store card with authorization if amount is assigned
            $res = array_merge($res, [
                'amount' => $this->getAmount(),
                'currency' => $this->getCurrency(),
                'with_register' => true,
                'transaction_indicator' => Preauthorize::TRANSACTION_INDICATOR_INITIAL,
            ]);
            $this->transactionClass = Preauthorize::class;
            $this->transactionName = 'preauthorize';
        }

        return $res;
    }

}
