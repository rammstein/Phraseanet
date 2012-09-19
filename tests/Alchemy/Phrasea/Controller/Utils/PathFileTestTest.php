<?php

require_once __DIR__ . '/../../../../PhraseanetWebTestCaseAbstract.class.inc';

class ControllerPathFileTestTest extends \PhraseanetWebTestCaseAbstract
{
    /**
     * Default route test
     */
    public function testRoutePath()
    {
        $file = new \SplFileObject(__DIR__ . '/../../../../testfiles/cestlafete.jpg');
        $this->client->request("GET", "/admin/tests/pathurl/path/", array('path' => $file->getPathname()));

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertEquals("application/json", $this->client->getResponse()->headers->get("content-type"));
        $content = json_decode($this->client->getResponse()->getContent());
        $this->assertTrue(is_object($content));
        $this->assertObjectHasAttribute('exists', $content);
        $this->assertObjectHasAttribute('file', $content);
        $this->assertObjectHasAttribute('dir', $content);
        $this->assertObjectHasAttribute('readable', $content);
        $this->assertObjectHasAttribute('executable', $content);
    }

    public function testRouteUrl()
    {
        $this->client->request("GET", "/admin/tests/pathurl/url/", array('url' => "www.google.com"));

        $response = $this->client->getResponse();
        $this->assertTrue($response->isOk());
        $this->assertEquals("application/json", $this->client->getResponse()->headers->get("content-type"));
        $content = json_decode($this->client->getResponse()->getContent());
        $this->assertTrue(is_object($content));
    }
}
