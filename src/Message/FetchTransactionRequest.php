<?php


namespace Ampeco\OmnipayBankart\Message;


use PaymentGateway\Client\StatusApi\StatusRequestData;

class FetchTransactionRequest extends Request
{
    public function getData()
    {
        return [
            'transaction_id' => $this->getTransactionId(),
            'transaction_reference' => $this->getTransactionReference(),
        ];
    }

    public function sendData($data)
    {
        $request = new StatusRequestData();
        $request->setTransactionUuid($data['transaction_reference']);
        $request->setMerchantTransactionId($data['transaction_id']);
        $result = $this->getClient()->sendStatusRequest($request);
        return $this->response = new Response($this, $result);
    }
}
