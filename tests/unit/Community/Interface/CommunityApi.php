<?php


abstract class CommunityApi extends BrowserKitTestCase
{
    protected $route = '/api/community/';
    protected $class = App\Community::class;
    protected $table = 'communities';
    protected $alwaysHidden = [];
    protected $defaultFields = [];
    protected $modelToUpdate;
}
