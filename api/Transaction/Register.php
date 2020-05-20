<?php

namespace PaymentGateway\Client\Transaction;

use PaymentGateway\Client\Transaction\Base\AbstractTransaction;
use PaymentGateway\Client\Transaction\Base\AddToCustomerProfileInterface;
use PaymentGateway\Client\Transaction\Base\AddToCustomerProfileTrait;
use PaymentGateway\Client\Transaction\Base\OffsiteInterface;
use PaymentGateway\Client\Transaction\Base\OffsiteTrait;
use PaymentGateway\Client\Transaction\Base\ScheduleInterface;
use PaymentGateway\Client\Transaction\Base\ScheduleTrait;

/**
 * Register: Register the customer's payment data for recurring charges.
 *
 * The registered customer payment data will be available for recurring transaction without user interaction.
 *
 * @package PaymentGateway\Client\Transaction
 */
class Register extends AbstractTransaction implements OffsiteInterface, ScheduleInterface, AddToCustomerProfileInterface {
    use OffsiteTrait;
    use ScheduleTrait;
    use AddToCustomerProfileTrait;
}