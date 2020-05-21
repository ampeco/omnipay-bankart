<?php


namespace Ampeco\OmnipayBankart;


interface ClientFactoryDataSource
{
    public function getUsername();
    public function getPassword();
    public function getApiKey();
    public function getSharedSecret();
    public function getLanguage();
}
