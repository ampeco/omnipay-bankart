<?php


namespace Ampeco\OmnipayBankart;


trait ContainsClientFactory
{

    public function setClientFactory(ClientFactory $factory): void
    {
        $this->setParameter('ClientFactory', $factory);
    }

    public function getClientFactory()
    {
        return $this->getParameter('ClientFactory');
    }

    protected function getClient(){
        if (!$this->getClientFactory()){
            return (new ClientFactory())->getClient($this);
        }

        return $this->getClientFactory()->getClient($this);
    }
}
