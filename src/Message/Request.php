<?php

namespace Ampeco\OmnipayBankart\Message;

use Omnipay\Common\Helper;
use Omnipay\Common\Message\AbstractRequest;
use PaymentGateway\Client\Client;
use PaymentGateway\Client\Data\Customer;
use PaymentGateway\Client\Transaction\Preauthorize;

abstract class Request extends AbstractRequest
{

    /**
     * @var string
     */
    protected $transactionClass;

    /**
     * @var string
     */
    protected $transactionName;

    public function setUsername($value)
    {
        return $this->setParameter('Username', $value);
    }

    public function getUsername()
    {
        return $this->getParameter('Username');
    }

    public function setPassword($value)
    {
        return $this->setParameter('Password', $value);
    }

    public function getPassword()
    {
        return $this->getParameter('Password');
    }

    public function setApiKey($value)
    {
        return $this->setParameter('ApiKey', $value);
    }

    public function getApiKey()
    {
        return $this->getParameter('ApiKey');
    }

    public function setSharedSecret($value)
    {
        return $this->setParameter('SharedSecret', $value);
    }

    public function getSharedSecret()
    {
        return $this->getParameter('SharedSecret');
    }

    /**
     * @return \PaymentGateway\Client\Client
     */
    protected function getClient(){
        Client::setApiUrl('https://gateway.bankart.si/');
        $client = new \PaymentGateway\Client\Client($this->getUsername(), $this->getPassword(), $this->getApiKey(), $this->getSharedSecret(), null, $this->getTestMode());
        return $client;
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {

        $class = $this->transactionClass;
        $transaction = new $class();
        foreach ($data as $key => $value){
            if ($key === 'customer' && is_array($value)){
                $customer = new Customer();
                foreach ($value as $customerKey => $customerValue){
                    call_user_func([$customer, Helper::camelCase('set_'.$customerKey)], $customerValue);
                }
                $transaction->setCustomer($customer);
            } else {
                call_user_func([$transaction, Helper::camelCase('set_'.$key)], $value);
            }
        }

        $result = call_user_func([$this->getClient(), $this->transactionName], $transaction);

        return $this->response = new Response($this, $result);
    }
}
