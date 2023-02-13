<?php

namespace Ampeco\OmnipayBankart\Message;

use Ampeco\OmnipayBankart\ClientFactoryDataSource;
use Ampeco\OmnipayBankart\ContainsClientFactory;
use Omnipay\Common\Helper;
use Omnipay\Common\Message\AbstractRequest;
use PaymentGateway\Client\Data\Customer;
use PaymentGateway\Client\Transaction\Register;

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

    public function get3dsAuthenticationIndicator()
    {
        return $this->getParameter('3dsAuthenticationIndicator');
    }

    public function set3dsAuthenticationIndicator($value)
    {
        return $this->setParameter('3dsAuthenticationIndicator', $value);
    }

    public function get3dsChallengeIndicator()
    {
        return $this->getParameter('3dsChallengeIndicator');
    }

    public function set3dsChallengeIndicator($value)
    {
        return $this->setParameter('3dsChallengeIndicator', $value);
    }

    public function get3dsRecurringFrequency()
    {
        return $this->getParameter('3dsRecurringFrequency');
    }

    public function set3dsRecurringFrequency($value)
    {
        return $this->setParameter('3dsRecurringFrequency', $value);
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {

        $class = $this->transactionClass;
        /**
         * @var $transaction Register
         */
        $transaction = new $class();
        foreach ($data as $key => $value) {
            if ($key === 'customer' && is_array($value)) {
                $customer = new Customer();
                foreach ($value as $customerKey => $customerValue) {
                    call_user_func([$customer, Helper::camelCase('set_' . $customerKey)], $customerValue);
                }
                $transaction->setCustomer($customer);
            } elseif ($key === 'extra_data' && is_array($value)) {
                foreach ($value as $extraKey => $extraValue) {
                    $transaction->addExtraData($extraKey, $extraValue);
                }
            } else {
                call_user_func([$transaction, Helper::camelCase('set_' . $key)], $value);
            }
        }

        $result = call_user_func([$this->getClient(), $this->transactionName], $transaction);

        return $this->response = new Response($this, $result);
    }
}
