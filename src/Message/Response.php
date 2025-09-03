<?php

namespace Ampeco\OmnipayBankart\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;
use PaymentGateway\Client\StatusApi\StatusResult;
use PaymentGateway\Client\Transaction\Result;

class Response extends AbstractResponse implements RedirectResponseInterface
{

    /**
     * @var \PaymentGateway\Client\Transaction\Result
     */
    protected $data;
    /**
     * Constructor.
     *
     * @param RequestInterface $request the initiating request.
     * @param Result|StatusResult $data
     */
    public function __construct(RequestInterface $request, $data)
    {
        parent::__construct($request, $data);
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful()
    {
        if ($this->data instanceof StatusResult){
            return $this->data->getTransactionStatus() === StatusResult::TRANSACTION_SUCCESS && $this->data->isOperationSuccess();
        }
        return $this->data->getReturnType() !== Result::RETURN_TYPE_ERROR && $this->data->isSuccess();
    }

    public function getCode()
    {
        $error = $this->data->getFirstError();
        if ($error){
            return $error->getCode();
        }

        return 0;
    }

    public function getMessage()
    {
        $error = $this->data->getFirstError();
        if ($error){
            return $error->getMessage();
        }

        return 0;
    }
    public function getTransactionReference()
    {
        if ($this->data instanceof StatusResult){
            return $this->data->getTransactionUuid();
        }
        return $this->data->getReferenceId();
    }

    public function getTransactionId()
    {
        if ($this->data instanceof StatusResult){
            return $this->data->getMerchantTransactionId();
        }
        return $this->data->getPurchaseId();
    }

    public function isRedirect(): bool
    {
        if ($this->data instanceof StatusResult){
            return false;
        }
        return $this->data->getReturnType() === Result::RETURN_TYPE_REDIRECT;
    }

    public function getRedirectUrl()
    {
        if ($this->data instanceof StatusResult){
            return null;
        }
        return $this->data->getRedirectUrl();
    }

    public function isOperationSuccess(): bool
    {
        return $this->data instanceof StatusResult ? $this->data->isOperationSuccess() : false;
    }

    public function getTransactionStatus(): ?string
    {
        return $this->data instanceof StatusResult ? $this->data->getTransactionStatus() : null;
    }

    public function getTransactionType(): ?string
    {
        return $this->data instanceof StatusResult ? $this->data->getTransactionType() : null;
    }
}
