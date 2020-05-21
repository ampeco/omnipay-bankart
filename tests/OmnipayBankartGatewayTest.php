<?php


namespace Ampeco\OmnipayBankartTests;
use Ampeco\OmnipayBankart\ClientFactory;
use Ampeco\OmnipayBankart\Gateway;
use Mockery;
use Omnipay\Omnipay;
use PaymentGateway\Client\Client;
use PaymentGateway\Client\Transaction\Capture;
use PaymentGateway\Client\Transaction\Debit;
use PaymentGateway\Client\Transaction\Deregister;
use PaymentGateway\Client\Transaction\Preauthorize;
use PaymentGateway\Client\Transaction\Refund;
use PaymentGateway\Client\Transaction\Register;
use PaymentGateway\Client\Transaction\Result;
use PaymentGateway\Client\Transaction\VoidTransaction;
use PHPUnit\Framework\TestCase;

class OmnipayBankartGatewayTest extends TestCase
{
    /**
     * @var Gateway|\Omnipay\Common\GatewayInterface
     */
    public $gateway;
    public $mockClient;

    protected function setUp(): void
    {
        parent::setUp();

        $this->gateway = Omnipay::create("\\".Gateway::class);

        $this->mockClient = Mockery::mock(Client::class);

        $clientMockFactory = Mockery::mock(ClientFactory::class, function (\Mockery\MockInterface $mock) {
            $mock->allows(['getClient' => $this->mockClient]);
        });
        $this->gateway->setClientFactory($clientMockFactory);
    }

    private function redirectResult(){
        $result = new Result;
        $result->setSuccess(true);
        $result->setRedirectType(Result::REDIRECT_TYPE_FULLPAGE);
        $result->setReturnType(Result::RETURN_TYPE_REDIRECT);
        $result->setRedirectUrl('http://google.com');
        $result->setReferenceId('123456');
        return $result;
    }

    private function successResult(){
        $result = new Result;
        $result->setSuccess(true);
        $result->setReturnType(Result::RETURN_TYPE_FINISHED);
        $result->setReferenceId('123456');
        return $result;
    }

    /**
     * @test
     */
    public function it_can_request_to_add_card_via_purchase(){
        $this->mockClient->shouldReceive('preauthorize')
            ->with(Mockery::on(function(Preauthorize $argument){
                if ($argument->getCurrency() != 'EUR'){
                    return false;
                }
                if (!$argument->isWithRegister()){
                    return false;
                }
                if ($argument->getAmount() != 1){
                    return false;
                }
                return true;
            }))
            ->andReturn($this->redirectResult())
            ->once();

        $this->mockClient->shouldNotReceive('register');

        $response = $this->gateway->createCard([
            'amount' => 1,
            'currency' => 'EUR'
        ])->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('http://google.com', $response->getRedirectUrl());
        $this->assertSame('123456', $response->getTransactionReference());
    }

    /**
     * @test
     */
    public function it_can_request_to_add_card_via_register(){
        $this->mockClient->shouldReceive('register')
            ->with(Mockery::capture($registerArg))
            ->andReturn($this->redirectResult())
            ->once();

        $this->mockClient->shouldNotReceive('preauthorize');


        $response = $this->gateway->createCard([
        ])->send();

        $this->assertInstanceOf(Register::class, $registerArg);

        $this->assertTrue($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('http://google.com', $response->getRedirectUrl());
        $this->assertSame('123456', $response->getTransactionReference());
    }


    /**
     * @test
     */
    public function it_can_purchase_with_a_saved_card(){
        $this->mockClient->shouldReceive('debit')
            ->with(Mockery::capture($request))
            ->andReturn($this->successResult())
            ->once();

        $this->mockClient->shouldNotReceive(['preauthorize', 'capture', 'refund', 'void']);


        $response = $this->gateway->purchase([
            'amount' => 10,
            'currency' => 'EUR',
            'description' => 'test',
            'card_reference' => 'refNo123'
        ])->send();

        $this->assertInstanceOf(Debit::class, $request);
        if ($request instanceof Debit){
            $this->assertSame('10.00', $request->getAmount());
            $this->assertSame('EUR', $request->getCurrency());
            $this->assertSame('test', $request->getDescription());
            $this->assertSame('refNo123', $request->getReferenceTransactionId());
        }

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(null, $response->getRedirectUrl());
        $this->assertSame('123456', $response->getTransactionReference());
    }

    /**
     * @test
     */
    public function it_can_authorize_with_a_saved_card(){
        $this->mockClient->shouldReceive('preauthorize')
            ->with(Mockery::capture($request))
            ->andReturn($this->successResult())
            ->once();

        $this->mockClient->shouldNotReceive(['debug', 'capture', 'refund', 'void']);


        $response = $this->gateway->authorize([
            'amount' => 10,
            'currency' => 'EUR',
            'description' => 'test',
            'card_reference' => 'refNo123'
        ])->send();

        $this->assertInstanceOf(Preauthorize::class, $request);
        if ($request instanceof Preauthorize){
            $this->assertSame('10.00', $request->getAmount());
            $this->assertSame('EUR', $request->getCurrency());
            $this->assertSame('test', $request->getDescription());
            $this->assertSame('refNo123', $request->getReferenceTransactionId());
        }

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(null, $response->getRedirectUrl());
        $this->assertSame('123456', $response->getTransactionReference());
    }

    /**
     * @test
     */
    public function it_can_capture_authorized_transaction(){
        $this->mockClient->shouldReceive('capture')
            ->with(Mockery::capture($request))
            ->andReturn($this->successResult())
            ->once();

        $this->mockClient->shouldNotReceive(['debug', 'preauthorize', 'refund', 'void']);


        $response = $this->gateway->capture([
            'amount' => 10,
            'currency' => 'EUR',
            'transaction_reference' => 'transaction_reference_8765'
        ])->send();

        $this->assertInstanceOf(Capture::class, $request);
        if ($request instanceof Capture){
            $this->assertSame('10.00', $request->getAmount());
            $this->assertSame('EUR', $request->getCurrency());
            $this->assertSame('transaction_reference_8765', $request->getReferenceTransactionId());
        }

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(null, $response->getRedirectUrl());
        $this->assertSame('123456', $response->getTransactionReference());
    }

    /**
     * @test
     */
    public function it_can_void_authorized_transaction(){
        $this->mockClient->shouldReceive('void')
            ->with(Mockery::capture($request))
            ->andReturn($this->successResult())
            ->once();

        $this->mockClient->shouldNotReceive(['debug', 'preauthorize', 'refund', 'capture']);


        $response = $this->gateway->void([
            'transaction_reference' => 'transaction_reference_8765'
        ])->send();

        $this->assertInstanceOf(VoidTransaction::class, $request);
        if ($request instanceof VoidTransaction){
            $this->assertSame('transaction_reference_8765', $request->getReferenceTransactionId());
        }

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(null, $response->getRedirectUrl());
        $this->assertSame('123456', $response->getTransactionReference());
    }

    /**
     * @test
     */
    public function it_can_refund_transaction(){
        $this->mockClient->shouldReceive('refund')
            ->with(Mockery::capture($request))
            ->andReturn($this->successResult())
            ->once();

        $this->mockClient->shouldNotReceive(['debug', 'preauthorize', 'void', 'capture']);


        $response = $this->gateway->refund([
            'transaction_reference' => 'transaction_reference_8765'
        ])->send();

        $this->assertInstanceOf(Refund::class, $request);
        if ($request instanceof Refund){
            $this->assertSame('transaction_reference_8765', $request->getReferenceTransactionId());
        }

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(null, $response->getRedirectUrl());
        $this->assertSame('123456', $response->getTransactionReference());
    }

    /**
     * @test
     */
    public function it_can_deregister_card(){
        $this->mockClient->shouldReceive('deregister')
            ->with(Mockery::capture($request))
            ->andReturn($this->successResult())
            ->once();

        $this->mockClient->shouldNotReceive(['debug', 'preauthorize', 'void', 'capture', 'refund']);


        $response = $this->gateway->deleteCard([
            'card_reference' => 'refNo123'
        ])->send();

        $this->assertInstanceOf(Deregister::class, $request);
        if ($request instanceof Deregister){
            $this->assertSame('refNo123', $request->getReferenceTransactionId());
        }

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(null, $response->getRedirectUrl());
        $this->assertSame('123456', $response->getTransactionReference());
    }
}
