<?php namespace Krumer\Test\Tools;

trait ApiTestToolsTrait {

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

        return $this->call($method, $uri, $parameters, $cookies, $files, $server, $content);
    }

    /**
     * Call the given URI as a Api/Ajax call and return the json decoded content.
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
    public function callApiAndReturnJsonDecodedContent($method, $uri, $parameters = [], $cookies = [], $files = [], $server = [], $content = null)
    {
        $response = $this->callApi($method, $uri, $parameters, $cookies, $files, $server, $content);

        $content = $response->getContent();

        return json_decode($content);
    }

}