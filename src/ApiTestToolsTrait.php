<?php namespace Krumer\Test\Tools;

trait ApiTestToolsTrait {

    protected $apiMethod;

    protected $apiUrl;

    protected $content;

    protected $info;

    protected $data;

    protected $error;

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
     * @return \Illuminate\Http\Response
     */
    protected function runApiCall($parameters = [])
    {
        return $this->callApi($this->apiMethod, $this->apiUrl, $parameters);
    }

    /**
     * Sets all available api variables based on the given content.
     *
     * @param string
     */
    protected function setupApiVariables($content)
    {
        $this->content = $content;

        $this->data = json_decode($content, true);

        if (is_array($this->data))
        {
            if (array_key_exists('info', $this->data))
            {
                $this->info = $this->data['info'];
            }

            if (array_key_exists('error', $this->data))
            {
                $this->error = $this->data['error'];
            }
        }
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
     * Call the api and assert that the response contains a validation error with the given validation errors.
     *
     * @param $parameters
     * @param $errors
     */
    protected function assertApiValidationFailed($parameters, $errors)
    {
        $this->runApiCall($parameters);

        $this->assertResponseStatus(400);
        $this->assertEquals('Validation failed', $this->error['message']);

        foreach ($errors as $field => $message)
        {
            $this->assertEquals($message, $this->error['details'][$field][0]);
        }
    }

    /**
     * Call the api and assert that the response contains a object not found error.
     *
     * @param $parameters
     */
    protected function assertApiObjectNotFound($parameters = [])
    {
        $this->runApiCall($parameters);

        $this->assertResponseStatus(400);
        $this->assertEquals('Object not found', $this->error['message']);
    }

    /**
     * Call the api and assert that the response contains a internal server error.
     *
     * @param $parameters
     */
    protected function assertApiInternalServerError($parameters = [])
    {
        $this->runApiCall($parameters);

        $this->assertResponseStatus(500);
    }

}