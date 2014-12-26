<?php namespace Tests\Tools;

use GuzzleHttp\Client;

trait MailcatcherTestToolsTrait {

    protected $mailcatcher;

    protected function setUpMailcatcherTools()
    {
        $this->setUpMailcatcher();
        $this->clearMails();
    }

    protected function setUpMailcatcher()
    {
        $config = $this->app->make('config');

        $mailcatcherConfig = array(
            'base_url' => $config['tests']['mailcatcher']['url'] . ':' . $config['tests']['mailcatcher']['port']
        );

        $this->mailcatcher = new Client($mailcatcherConfig);
    }

    protected function clearMails()
    {
        $this->mailcatcher->delete('/messages');
    }

    /**
     * Get an array of all the message objects.
     *
     * @return mixed
     */
    protected function messages()
    {
        $response = $this->mailcatcher->get('/messages');
        $messages = $response->json();

        if (empty($messages))
        {
            $this->fail("No messages received");
        }

        return $messages;
    }

    /**
     * Get the most recent email.
     *
     * @return mixed
     */
    protected function lastMessage()
    {
        $messages = $this->messages();

        $last = array_shift($messages);

        return $this->emailFromId($last['id']);
    }

    /**
     * Look for a regex in the email source and return it's matches.
     *
     * @param $regex
     * @return mixed
     */
    protected function grabMatchesFromLastEmail($regex)
    {
        $email = $this->lastMessage();
        $matches = $this->grabMatchesFromEmail($email, $regex);
        return $matches;
    }

    /**
     * Look for a regex in the email source and return it.
     *
     * @param $regex
     * @return mixed
     */
    protected function grabFromLastEmail($regex)
    {
        $matches = $this->grabMatchesFromLastEmail($regex);
        return $matches[0];
    }

    /**
     * Look for a regex in most recent email source sent to specific email address and return it's matches.
     *
     * @param $address
     * @param $regex
     * @return mixed
     */
    protected function grabMatchesFromLastEmailTo($address, $regex)
    {
        $email = $this->lastMessageFrom($address);
        $matches = $this->grabMatchesFromEmail($email, $regex);
        return $matches;
    }

    /**
     * Look for a regex in most recent email source sent to a specific email address and return it.
     *
     * @param $address
     * @param $regex
     * @return mixed
     */
    public function grabFromLastEmailTo($address, $regex)
    {
        $matches = $this->grabMatchesFromLastEmailTo($address, $regex);
        return $matches[0];
    }

    /**
     * Returns the email's object given the Mailcatcher id
     *
     * @param $id
     * @return mixed
     */
    protected function emailFromId($id)
    {
        $response = $this->mailcatcher->get("/messages/{$id}.json");
        return $response->json();
    }

    /**
     * Get the most recent email sent to the given email address.
     *
     * @param $address
     * @return mixed
     */
    protected function lastMessageFrom($address)
    {
        $messages = $this->messages();
        foreach ($messages as $message) {
            foreach ($message['recipients'] as $recipient) {
                if (strpos($recipient, $address) !== false) {
                    return $this->emailFromId($message['id']);
                }
            }
        }
        $this->fail("No messages sent to {$address}");
    }

    /**
     * Return the matches of a regex against the raw email source.
     *
     * @param $email
     * @param $regex
     * @return mixed
     */
    protected function grabMatchesFromEmail($email, $regex)
    {
        $source = utf8_encode(quoted_printable_decode($email['source']));
        preg_match($regex, $source, $matches);
        $this->assertNotEmpty($matches, "No matches found for $regex");
        return $matches;
    }

}