<?php

namespace Ampeco\OmnipayBankart;

use Ampeco\OmnipayBankart\Message\AcceptNotification;
use Ampeco\OmnipayBankart\Message\AuthorizeRequest;
use Ampeco\OmnipayBankart\Message\CaptureRequest;
use Ampeco\OmnipayBankart\Message\CreateCardRequest;
use Ampeco\OmnipayBankart\Message\DeleteCardRequest;
use Ampeco\OmnipayBankart\Message\FetchTransactionRequest;
use Ampeco\OmnipayBankart\Message\PurchaseRequest;
use Ampeco\OmnipayBankart\Message\RefundRequest;
use Ampeco\OmnipayBankart\Message\VoidRequest;
use Omnipay\Common\AbstractGateway;
use PaymentGateway\Client\Client;

/**
 * @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
 * @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
 */
class Gateway extends AbstractGateway implements ClientFactoryDataSource
{
    use ContainsClientFactory;

    public function getName()
    {
        return 'Bankart';
    }

    /**
     * @return array
     */
    public function getDefaultParameters()
    {
        return [
            'Username' => '',
            'Password' => '',
            'ApiKey' => '',
            'SharedSecret' => '',
            'Language' => 'en',
        ];
    }
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

    public function createCard(array $parameters = array())
    {
        return $this->createRequest(CreateCardRequest::class, $parameters);
    }

    public function deleteCard(array $parameters = array())
    {
        return $this->createRequest(DeleteCardRequest::class, $parameters);
    }

    public function authorize(array $parameters = array())
    {
        return $this->createRequest(AuthorizeRequest::class, $parameters);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest(PurchaseRequest::class, $parameters);
    }

    public function capture(array $parameters = array())
    {
        return $this->createRequest(CaptureRequest::class, $parameters);
    }
    public function void(array $parameters = array())
    {
        return $this->createRequest(VoidRequest::class, $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest(RefundRequest::class, $parameters);
    }

    public function fetchTransaction(array $parameters = [])
    {
        return $this->createRequest(FetchTransactionRequest::class, $parameters);
    }

    public function acceptNotification(array $parameters = array())
    {
        $client = $this->getClient();
        if ($client->validateCallbackWithGlobals()){
            $requestBody = file_get_contents('php://input');
            $res = $client->readCallback($requestBody);
            return new AcceptNotification($res);
        }
        return new AcceptNotification();
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completeAuthorize(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface completePurchase(array $options = array())
        // TODO: Implement @method \Omnipay\Common\Message\RequestInterface updateCard(array $options = array())
    }
}
