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
        $this->content = $response->getContent();
        $decodedContent = $this->decodeJsonData($this->content);

        if (is_array($decodedContent))
        {
            if (array_key_exists('info', $decodedContent))
            {
                $this->info = $decodedContent['info'];
            }
            
            if (array_key_exists('data', $decodedContent))
            {
                $this->data = $decodedContent['data'];
            }

            if (array_key_exists('error', $decodedContent))
            {
                $this->error = $decodedContent['error'];
            }
        }

        return $response;
    }

    /**
     * Returns the retrieved content json encoded.
     *
     * @param string
     * @return mixed
     */
    public function decodeJsonData($data)
    {
        return json_decode($data, true);
    }

}