<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Setting;

class SettingModelTest extends TestCase
{

    public function setUp(){
        parent::setUp();
        $this->setSettings(['testsetting'=>"Setting values"]);
        $this->setSettings(['testnestedsetting.nested'=>""]);
    }

    public function tearDown(){
      $this->restoreSettings();
      parent::tearDown();
    }

    /** @test */
    public function can_set_new_nested_value_in_testcase()
    {
        $value = Faker\Factory::create()->sentence;
        $this->setSettings(['notset.nested'=>$value]);
        $this->assertEquals(Setting::get('notset.nested'), $value);
    }

    /** @test */
    public function can_set_new_nested_value()
    {
        $oldValue = Setting::get('testsetting');
        Setting::ifNotSetThenSet('oldSetting','test value');
        $this->assertEquals(Setting::get('testsetting'), $oldValue);
    }

    /** @test */
    public function if_not_set_value_the_set_value_on_json_not_set()
    {
        Setting::ifNotSetThenSet('testkey', 'test value');
        $this->assertTrue(array_key_exists('testkey', Setting::all()));
    }

    /** @test */
    public function rename_setting_creates_new_name(){
        Setting::renameSetting('testsetting', 'testsettingrenamed');
        $this->assertTrue(array_key_exists('testsettingrenamed', Setting::all()));
        $this->assertFalse(array_key_exists('testsetting', Setting::all()));

    }

    /** @test */
    public function rename_setting_fails_if_setting_already_exists(){
        $initialSettings = Setting::all();
        Setting::renameSetting('paywall.on','testsetting');
        $this->assertEquals($initialSettings, Setting::all());
    }
}
