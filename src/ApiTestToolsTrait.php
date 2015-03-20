<?php namespace Krumer\Test\Tools;

trait ApiTestToolsTrait {

    protected $apiMethod;

    protected $apiUrl;

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

    /**
     * Call the api with the predefined method and url.
     *
     * @param array $parameters
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null $content
     * @return \Illuminate\Http\Response
     */
    protected function fireApiCall($parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        return $this->callApi($this->apiMethod, $this->apiUrl, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * Set the url of the API.
     *
     * @param $apiUrl
     */
    public function setApiUrl($apiUrl)
    {
        $this->apiUrl = $apiUrl;
    }

    /**
     * Return the url of the API.
     *
     * @return mixed
     */
    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * Set the method of the API.
     *
     * @param $apiMethod
     */
    protected function setApiMethod($apiMethod)
    {
        $this->apiMethod = $apiMethod;
    }

    /**
     * Return the method of the API.
     *
     * @return mixed
     */
    protected function getApiMethod()
    {
        return $this->apiMethod;
    }

    /**
     * Assert that the api response contains a validation error with the given validation errors.
     *
     * @param $errors
     */
    protected function assertApiResponseValidationFailed($errors)
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
    protected function assertApiResponseObjectNotFound()
    {
        $this->assertResponseStatus(400);
        $this->assertEquals('Object not found', $this->apiError['message']);
    }

    /**
     * Assert that the api response contains a internal server error.
     */
    protected function assertApiResponseInternalServerError()
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