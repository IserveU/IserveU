<?php


abstract class AuthenticateApi extends BrowserKitTestCase
{
    protected $route = '/Authenticate';
    protected $class = App\User::class; //Usually
    protected $table = 'users';
    protected $alwaysHidden = [];
    protected $defaultFields = [];
    protected $modelToUpdate;
}
