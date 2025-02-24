<?php


namespace Box\Mod\System\Api;


class AdminTest extends \BBTestCase {
    /**
     * @var \Box\Mod\System\Api\Admin
     */
    protected $api = null;

    public function setup(): void
    {
        $this->api= new \Box\Mod\System\Api\Admin();
    }

    public function testgetDi()
    {
        $di = new \Pimple\Container();
        $this->api->setDi($di);
        $getDi = $this->api->getDi();
        $this->assertEquals($di, $getDi);
    }

    public function testget_params()
    {
        $data = array(
        );

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\System\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getParams')
            ->will($this->returnValue(array()));

        $this->api->setService($serviceMock);

        $result = $this->api->get_params($data);
        $this->assertIsArray($result);
    }

    public function testupdate_params()
    {
        $data = array(
        );

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\System\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('updateParams')
            ->will($this->returnValue(true));

        $this->api->setService($serviceMock);

        $result = $this->api->update_params($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function testmessages()
    {
        $data = array(
        );

        $di = new \Pimple\Container();

        $this->api->setDi($di);

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\System\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getMessages')
            ->will($this->returnValue(array()));

        $this->api->setService($serviceMock);

        $result = $this->api->messages($data);
        $this->assertIsArray($result);
    }

    public function testtemplate_exists()
    {
        $data = array(
            'file' => 'testing.txt',
        );

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\System\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('templateExists')
            ->will($this->returnValue(true));

        $this->api->setService($serviceMock);

        $result = $this->api->template_exists($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }

    public function teststring_render()
    {
        $data = array(
            '_tpl' => 'default'
        );

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\System\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('renderString')
            ->will($this->returnValue('returnStringType'));
        $di = new \Pimple\Container();

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->string_render($data);
        $this->assertIsString($result);
    }

    public function testenv()
    {
        $data = array();

        $serviceMock = $this->getMockBuilder('\\' . \Box\Mod\System\Service::class)->getMock();
        $serviceMock->expects($this->atLeastOnce())
            ->method('getEnv')
            ->will($this->returnValue(array()));

        $di = new \Pimple\Container();

        $this->api->setDi($di);
        $this->api->setService($serviceMock);

        $result = $this->api->env($data);
        $this->assertIsArray($result);
    }

    public function testis_allowed()
    {
        $data = array(
            'mod' => 'extension',
        );

        $staffServiceMock = $this->getMockBuilder('\\' . \Box\Mod\Staff\Service::class)->getMock();
        $staffServiceMock->expects($this->atLeastOnce())
            ->method('hasPermission')
            ->will($this->returnValue(true));

        $validatorMock = $this->getMockBuilder('\\' . \FOSSBilling\Validate::class)->disableOriginalConstructor()->getMock();
        $validatorMock->expects($this->atLeastOnce())
            ->method('checkRequiredParamsForArray')
            ->will($this->returnValue(null));

        $di                = new \Pimple\Container();
        $di['mod_service'] = $di->protect(function ($serviceName) use ($staffServiceMock) {
            if ($serviceName == 'Staff') {
                return $staffServiceMock;
            }

            return false;
        });

        $di['validator'] = $validatorMock;

        $this->api->setDi($di);

        $result = $this->api->is_allowed($data);
        $this->assertIsBool($result);
        $this->assertTrue($result);
    }
}
