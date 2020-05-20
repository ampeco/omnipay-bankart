<?php

namespace PaymentGateway\Client\Transaction;

use PaymentGateway\Client\Transaction\Base\AbstractTransactionWithReference;
use PaymentGateway\Client\Transaction\Base\AmountableInterface;
use PaymentGateway\Client\Transaction\Base\AmountableTrait;
use PaymentGateway\Client\Transaction\Base\ItemsInterface;
use PaymentGateway\Client\Transaction\Base\ItemsTrait;

/**
 * Payout: Payout a certain amount of money to the customer. (Debits the merchant's account, Credits the customer's account)
 *
 * @package PaymentGateway\Client\Transaction
 */
class Payout extends AbstractTransactionWithReference implements AmountableInterface, ItemsInterface {
    use ItemsTrait;
    use AmountableTrait;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $callbackUrl;

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCallbackUrl() {
        return $this->callbackUrl;
    }

    /**
     * @param string $callbackUrl
     */
    public function setCallbackUrl($callbackUrl) {
        $this->callbackUrl = $callbackUrl;
    }

}