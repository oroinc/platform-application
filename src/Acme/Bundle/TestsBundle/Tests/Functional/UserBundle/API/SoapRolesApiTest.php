<?php

namespace Acme\Bundle\TestsBundle\Tests\Functional\API;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Acme\Bundle\TestsBundle\Test\ToolsAPI;

/**
 * @outputBuffering enabled
 * @runTestsInSeparateProcesses
 */
class SoapRolesApiTest extends WebTestCase
{
    /** Default value for role label */
    const DEFAULT_VALUE = 'ROLE_LABEL';

    /** @var \SoapClient */
    protected $clientSoap = null;

    public function setUp()
    {
        $this->clientSoap = static::createClient(array('debug' => true));
        $this->clientSoap->soap(
            "http://localhost/api/soap",
            array('location' => 'http://localhost/api/soap',
                'soap_version' => SOAP_1_2
            )
        );
    }

    /**
     * @runInSeparateProcess
     *
     * @param string $request
     * @param array  $response
     *
     * @dataProvider requestsApi
     */
    public function testCreateRole($request, $response)
    {
        if (is_null($request['role'])) {
            $request['role'] ='';
        }
        if (is_null($request['label'])) {
            $request['label'] = self::DEFAULT_VALUE;
        }
        $result =  $this->clientSoap->soapClient->createRole($request);
        $result = ToolsAPI::classToArray($result);
        ToolsAPI::assertEqualsResponse($response, $result);
    }

    /**
     * @runInSeparateProcess
     *
     * @param string $request
     * @param array  $response
     *
     * @dataProvider requestsApi
     * @depends testCreateRole
     */
    public function testUpdateRole($request, $response)
    {
        if (is_null($request['role'])) {
            $request['role'] ='';
        }
        if (is_null($request['label'])) {
            $request['label'] = self::DEFAULT_VALUE;
        }
        $request['label'] .= '_Updated';
        //get role id
        $roleId =  $this->clientSoap->soapClient->getRoleByName($request['role']);
        $roleId = ToolsAPI::classToArray($roleId);
        $result =  $this->clientSoap->soapClient->updateRole($roleId['id'], $request);
        $result = ToolsAPI::classToArray($result);
        ToolsAPI::assertEqualsResponse($response, $result);
        $role =  $this->clientSoap->soapClient->getRole($roleId['id']);
        $role = ToolsAPI::classToArray($role);
        $this->assertEquals($request['label'], $role['label']);
    }

    /**
     * @runInSeparateProcess
     *
     * @depends testUpdateRole
     * @return array
     */
    public function testGetRole()
    {
        //get roles
        $roles =  $this->clientSoap->soapClient->getRoles();
        $roles = ToolsAPI::classToArray($roles);
        //filter roles
        $roles = array_filter(
            $roles['item'],
            function ($v) {
                return $v['role']. '_UPDATED' == strtoupper($v['label']);
            }
        );
        $this->assertEquals(3, count($roles));

        return $roles;
    }

    /**
     * @runInSeparateProcess
     *
     * @depends testGetRole
     * @param array $roles
     */
    public function testDeleteRoles($roles)
    {
        //get roles
        foreach ($roles as $role) {
            $result =  $this->clientSoap->soapClient->deleteRole($role['id']);
            $this->assertTrue($result);
        }
        $roles =  $this->clientSoap->soapClient->getRoles();
        $roles = ToolsAPI::classToArray($roles);
        if (!empty($roles)) {
            $roles = array_filter(
                $roles['item'],
                function ($v) {
                    return $v['role']. '_UPDATED' == strtoupper($v['label']);
                }
            );
        }
        $this->assertEmpty($roles);
    }

    /**
     * Data provider for REST API tests
     *
     * @return array
     */
    public function requestsApi()
    {
        return ToolsAPI::requestsApi(__DIR__ . DIRECTORY_SEPARATOR . 'RoleRequest');
    }
}
