<?php namespace Krumer\Test\Tools;

use Krumer\Test\Tools\Utils\ContentSearch;

trait ApiTestToolsTrait {

    protected $uri = '';

    protected $parameters = [];

    protected $content;

    protected $info;

    protected $error;


    /**
     * Clear the previous parameters of the api call.
     *
     * @tearDown
     */
    protected function clear()
    {
        $this->uri = '';
        $this->parameters = [];

        unset($this->content);
    }

    /**
     * Set the uri for the api call.
     *
     * @param $uri
     */
    protected function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * Append an additional part to the current uri.
     *
     * @param $part
     */
    protected function appendToUri($part)
    {

        if (substr($this->uri, -1) !== '/')
        {
            $this->uri .= '/';
        }

        $this->uri .= $part;
    }

    /**
     * Set the parameters for the api call.
     *
     * @param array $overrides
     * @return array
     */
    protected function setRequestParameters(array $overrides = [])
    {
        $defaults = $this->getDefaultRequestParameters();

        $this->parameters = array_merge($defaults, $overrides);

        return $this->parameters;
    }

    /**
     * Return the default parameters for the tests.
     *
     * @return array
     */
    protected function getDefaultRequestParameters()
    {
        return [];
    }

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
        $this->decodeContent($response->getContent());

        return $response;
    }

    /**
     * Decode the content and set all variables.
     *
     * @param $content
     */
    private function decodeContent($content)
    {
        $this->content = json_decode($content, true);

        if (is_array($this->content))
        {
            if (array_key_exists('info', $this->content))
            {
                $this->info = $this->content['info'];
            }

            if (array_key_exists('error', $this->content))
            {
                $this->error = $this->content['error'];
            }
        }
    }

    /**
     * Run a get request with the set settings.
     *
     * @return \Illuminate\Http\Response
     */
    protected function get()
    {
        return $this->callApi('GET', $this->uri, $this->parameters);
    }

    /**
     * Run a post request with the set settings.
     *
     * @return \Illuminate\Http\Response
     */
    protected function post()
    {
        return $this->callApi('POST', $this->uri, $this->parameters);
    }

    /**
     * Run a put request with the set settings.
     *
     * @return \Illuminate\Http\Response
     */
    protected function put()
    {
        return $this->callApi('PUT', $this->uri, $this->parameters);
    }

    /**
     * Run a delete request with the set settings.
     *
     * @return \Illuminate\Http\Response
     */
    protected function delete()
    {
        return $this->callApi('DELETE', $this->uri, $this->parameters);
    }


    /**
     * Assert that the api response contains a validation error.
     */
    protected function assertResponseValidationFailed()
    {
        $this->assertResponseStatus(400);
        $this->assertEquals('Validation failed', $this->error['message']);
    }

    /**
     * Assert that the api response contains a object not found error.
     */
    protected function assertResponseObjectNotFound()
    {
        $this->assertResponseStatus(400);
        $this->assertEquals('Object not found', $this->error['message']);
    }

    /**
     * Assert that the api response contains an unauthorized error.
     */
    protected function assertResponseUnauthorized()
    {
        $this->assertResponseStatus(401);
    }

    /**
     * Assert that the api response contains a internal server error.
     */
    protected function assertResponseInternalServerError()
    {
        $this->assertResponseStatus(500);
    }

    /**
     * Assert that the content has a field with the given value.
     *
     * @param $field
     * @param $value
     */
    protected function assertContentHas($field, $value)
    {
        $fields = ContentSearch::findSearchFields($field, $this->content);

        $found = false;

        foreach ($fields as $field)
        {
            if ($value == $field)
            {
                $found = true;
                break;
            }
        }

        $this->assertTrue($found);
    }

    /**
     * Assert that the validation errors contain a given message for the specified field.
     *
     * @param $field
     * @param $message
     */
    protected function assertValidationErrorsHave($field, $message)
    {
        $this->assertContains($message, $this->error['details'][$field]);
    }

}