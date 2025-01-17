<?php

namespace Veneridze\Autologin\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Veneridze\Autologin\Autologin;
use Veneridze\Autologin\Interfaces\AuthenticationInterface;
use Veneridze\Autologin\Interfaces\AutologinInterface;

class AutologinController extends Controller
{
    /**
     * AuthenticationInterface provider instance.
     *
     * @var \Veneridze\Autologin\Interfaces\AuthenticationInterface
     */
    protected $provider;

    /**
     * Studious Autologin instance.
     *
     * @var \Veneridze\Autologin\Autologin
     */
    protected $autologin;

    /**
     * Instantiate the controller.
     *
     * @param  \Veneridze\Autologin\Interfaces\AuthenticationInterface  $authProvider
     * @param  \Veneridze\Autologin\Autologin                           $autologin
     * @return void
     */
    //AuthenticationInterface $authProvider, Autologin $autologin
    public function __construct()
    {
        $this->provider = App::make(AuthenticationInterface::class); //$authProvider
        $this->autologin = App::make(Autologin::class);//$autologin;
    }

    /**
     * Process the autologin token and perform the redirect.
     *
     * @param  string  $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(string $token)
    {
        if ($autologin = $this->autologin->validate($token)) {
            // Active token found, login the user and redirect to the
            // intended path.
            if ($user = $this->provider->loginUsingId($autologin->getUserId())) {
                return redirect($autologin->getPath());
            }
        }

        // Token was invalid, redirect back to the home page.
        return redirect('/');
    }
}
