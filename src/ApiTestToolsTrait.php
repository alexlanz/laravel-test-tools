<?php namespace Krumer\Test\Tools;

trait ApiTestToolsTrait {

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

}