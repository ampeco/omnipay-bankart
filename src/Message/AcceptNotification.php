<?php


namespace Ampeco\OmnipayBankart\Message;


use Omnipay\Common\Message\NotificationInterface;
use PaymentGateway\Client\Callback\Result;

class AcceptNotification implements NotificationInterface
{

    protected $data;
    public function __construct(?Result $data = null)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }


    public function getTransactionReference()
    {
        if (!$this->data){
            return null;
        }
        return $this->data->getReferenceId();
    }

    public function getTransactionId()
    {
        if (!$this->data){
            return null;
        }
        return $this->data->getTransactionId();
    }

    public function getTransactionStatus()
    {
        if (!$this->data){
            return NotificationInterface::STATUS_FAILED;
        }
        if ($this->data->getResult() == Result::RESULT_OK){
            return NotificationInterface::STATUS_COMPLETED;
        }
        if ($this->data->getResult() == Result::RESULT_PENDING){
            return NotificationInterface::STATUS_PENDING;
        }

        return NotificationInterface::STATUS_FAILED;
    }

    public function getMessage()
    {
        if (!$this->data){
            return null;
        }
        $error = $this->data->getFirstError();
        if (!$error){
            return null;
        }

        return $error->getMessage();
    }
}
