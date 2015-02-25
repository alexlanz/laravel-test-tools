<?php namespace Krumer\Test\Tools;

trait AuthenticationTestToolsTrait {

    protected function setUpAuthenticationTools($email = 'max@mustermann.it', $password = 'password')
    {
        $this->login($email, $password);
    }


    /**
     * Log in as the given user with the given password.
     *
     * @param $email
     * @param $password
     */
    public function login($email, $password)
    {
        $credentials = [
            'email'    => $email,
            'password' => $password
        ];

        $this->app['auth']->attempt($credentials);
    }

}