<?php


namespace Box\Mod\Servicelicense\Api;


class ClientTest extends \BBTestCase {
    /**
     * @var \Box\Mod\Servicelicense\Api\Client
     */
    protected $api = null;

    public function setup(): void
    {
        $this->api= new \Box\Mod\Servicelicense\Api\Client();
    }

    public function testgetDi()
    {
        $di = new \Pimple\Container();
        $this->api->setDi($di);
        $getDi = $this->api->getDi();
        $this->assertEquals($di, $getDi);
    }

    public function testreset()
    {
        $data = array(
            'order_id' => 1,
        );

        $apiMock = $this->getMockBuilder('\\' . \Box\Mod\Servicelicense\Api\Admin::class)
            ->onlyMethods(array('_getService'))
            ->getMock();
        $apiMock->expects($this->atLeastOnce())
            ->method('_getService')
            ->will($this->returnValue(new \Model_ServiceLicense()));

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\Servicelicense\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('reset')
            ->will($this->returnValue(true));

        $apiMock->setService($serviceMock);
        $result = $apiMock->reset($data);

        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function test_getService()
    {
        $data['order_id'] = 1;

        $orderServiceMock= $this->getMockBuilder('\\' . \Box\Mod\Order\Service::class)->getMock();
        $orderServiceMock->expects($this->atLeastOnce())
            ->method('getOrderService')
            ->will($this->returnValue(new \Model_ServiceLicense()));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('findOne')
            ->with('ClientOrder')
            ->will($this->returnValue(new \Model_ClientOrder()));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;
        $di['mod_service'] = $di->protect(fn() => $orderServiceMock);

        $this->api->setDi($di);

        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());
        $this->api->setIdentity($clientModel);

        $result = $this->api->_getService($data);
        $this->assertInstanceOf('\Model_ServiceLicense', $result);
    }

    public function test_getServiceOrderNotActivated()
    {
        $data['order_id'] = 1;

        $orderServiceMock= $this->getMockBuilder('\\' . \Box\Mod\Order\Service::class)->getMock();
        $orderServiceMock->expects($this->atLeastOnce())
            ->method('getOrderService')
            ->will($this->returnValue(null));

        $dbMock = $this->getMockBuilder('\Box_Database')->getMock();
        $dbMock->expects($this->atLeastOnce())
            ->method('findOne')
            ->with('ClientOrder')
            ->will($this->returnValue(new \Model_ClientOrder()));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray');

        $di = new \Pimple\Container();
        $di['validator'] = $validatorMock;
        $di['db'] = $dbMock;
        $di['mod_service'] = $di->protect(fn() => $orderServiceMock);

        $this->api->setDi($di);

        $clientModel = new \Model_Client();
        $clientModel->loadBean(new \DummyBean());
        $this->api->setIdentity($clientModel);

        $this->expectException(\FOSSBilling\Exception::class);
        $this->expectExceptionMessage('Order is not activated');
        $this->api->_getService($data);
    }
}
