<?php namespace Krumer\Test\Tools;

trait AuthenticationTestToolsTrait {

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

    /**
     * Log out the current user.
     */
    public function logout()
    {
        $this->app['auth']->logout();
    }

    /**
     * Return the currently authenticated user.
     *
     * @return mixed
     */
    public function getAuthenticatedUser()
    {
        return $this->app['auth']->user();
    }

}