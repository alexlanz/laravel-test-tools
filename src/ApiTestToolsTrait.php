<?php namespace Krumer\Test\Tools;

trait ApiTestToolsTrait {

    protected $apiData;

    protected $apiInfo;

    protected $apiError;

    /**
     * Call the given URI as a Api/Ajax call and return the Response.
     *
     * @param string $method
     * @param string $uri
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @return \Illuminate\Http\Response
     */
    public function callApi($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        // Set headers for a valid ajax request
        $server['HTTP_X-Requested-With'] = 'XMLHttpRequest';

        // Call
        $response = $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);

        // Set variables
        $this->setupApiVariables($response->getContent());

        return $response;
    }

    protected function get($uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->callApi('GET', $uri, $parameters, $cookies, $files, $server, $content);
    }

    protected function post($uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->callApi('POST', $uri, $parameters, $cookies, $files, $server, $content);
    }

    protected function put($uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->callApi('POST', $uri, $parameters, $cookies, $files, $server, $content);
    }

    protected function delete($uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->callApi('DELETE', $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * Assert that the api response contains a successful response.
     */
    protected function assertSuccess()
    {
        $this->assertResponseStatus(200);
    }

    /**
     * Assert that the api response contains a validation error with the given validation errors.
     *
     * @param $errors
     */
    protected function assertValidationFailed($errors)
    {
        $this->assertResponseStatus(400);
        $this->assertEquals('Validation failed', $this->apiError['message']);

        foreach ($errors as $field => $message)
        {
            $this->assertEquals($message, $this->apiError['details'][$field][0]);
        }
    }

    /**
     * Assert that the api response contains a object not found error.
     */
    protected function assertObjectNotFound()
    {
        $this->assertResponseStatus(400);
        $this->assertEquals('Object not found', $this->apiError['message']);
    }

    /**
     * Assert that the api response contains a internal server error.
     */
    protected function assertInternalServerError()
    {
        $this->assertResponseStatus(500);
    }

    /**
     * Sets all available api variables based on the given content.
     *
     * @param string
     */
    protected function setupApiVariables($content)
    {
        $this->apiData = json_decode($content, true);

        if (is_array($this->apiData))
        {
            if (array_key_exists('info', $this->apiData))
            {
                $this->apiInfo = $this->apiData['info'];
            }

            if (array_key_exists('error', $this->apiData))
            {
                $this->apiError = $this->apiData['error'];
            }
        }
    }

}