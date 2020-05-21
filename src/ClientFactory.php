<?php


namespace Ampeco\OmnipayBankart;


use PaymentGateway\Client\Client;

class ClientFactory
{
    public function getClient(ClientFactoryDataSource $dataSource){
        Client::setApiUrl('https://gateway.bankart.si/');
        return new \PaymentGateway\Client\Client(
            $dataSource->getUsername(),
            $dataSource->getPassword(),
            $dataSource->getApiKey(),
            $dataSource->getSharedSecret(),
            $dataSource->getLanguage()
        );
    }
}
