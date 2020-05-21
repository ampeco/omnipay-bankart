<?php

namespace Ampeco\OmnipayBankart\Message;

use Ampeco\OmnipayBankart\ClientFactory;
use Ampeco\OmnipayBankart\ClientFactoryDataSource;
use Ampeco\OmnipayBankart\ContainsClientFactory;
use Omnipay\Common\Helper;
use Omnipay\Common\Message\AbstractRequest;
use PaymentGateway\Client\Client;
use PaymentGateway\Client\Data\Customer;
use PaymentGateway\Client\Transaction\Preauthorize;

abstract class Request extends AbstractRequest implements ClientFactoryDataSource
{
    use ContainsClientFactory;

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

    public function setLanguage($value)
    {
        return $this->setParameter('Language', $value);
    }

    public function getLanguage()
    {
        return $this->getParameter('Language');
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
