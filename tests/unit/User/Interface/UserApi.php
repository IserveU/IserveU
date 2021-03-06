<?php


abstract class UserApi extends BrowserKitTestCase
{
    protected $route = '/api/user/';
    protected $class = App\User::class;
    protected $table = 'users';
    protected $alwaysHidden = ['password'];
    protected $defaultFields = ['email', 'password', 'first_name', 'last_name'];
    protected $modelToUpdate;
}
