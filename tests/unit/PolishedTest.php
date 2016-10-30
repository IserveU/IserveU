<?php

use App\Permission;
use App\Role;
use Carbon\Carbon;

trait PolishedTest
{
    protected $settings = [];
    protected $env = [];

    protected $files = [
        'test.png' => [
                'name'          => 'test.png',
                'type'          => 'image/png',
            ],
        'test_a.jpg' => [
                'name'          => 'test_a.jpg',
                'type'          => 'image/jpeg',
            ],
        'test_b.jpg' => [
                'name'          => 'test_b.jpg',
                'type'          => 'image/jpeg',
            ],
        'test_b.pdf' => [
                'name'          => 'test_b.pdf',
                'type'          => 'application/pdf',
            ],
    ];

    public function setSettings($temporarySettings)
    {
        foreach ($temporarySettings as $key => $value) {
            $this->settings[$key] = \App\Setting::get($key); //Getting the before value
            \App\Setting::set($key, $value);
        }
        \App\Setting::save();
    }

    public function restoreSettings()
    {
        foreach ($this->settings as $key => $value) {
            if ($value == null) {
                \App\Setting::forget($key);
            } else {
                $this->settings[$key] = \App\Setting::get($key);
                \App\Setting::set($key, $value);
            }
        }

        \App\Setting::save();
        $this->settings = [];
    }

    public function setEnv($temporaryEnv)
    {
        foreach ($temporaryEnv as $key => $value) {
            $this->env[$key] = getenv($key); //Getting the before value
            putenv("$key=$value");
        }
    }

    public function restoreEnv()
    {
        foreach ($this->env as $key => $value) {
            putenv("$key=$value");
        }
        $this->env = [];
    }

    public function setUp()
    {
        parent::setUp();

        foreach (\File::files(base_path('storage/logs')) as $filename) {
            File::delete($filename);
        }

        \Config::set('mail.driver', 'log');
    }

    public function tearDown()
    {
        if (!empty($this->settings)) {
            $this->restoreSettings();
        }

        if (!empty($this->env)) {
            $this->restoreEnv();
        }

        parent::tearDown();
    }

    public function signIn($user = null)
    {
        if (!$user) {
            $user = factory(App\User::class)->create();
        }

        $this->user = $user;

        $this->actingAs($this->user);

        return $this;
    }

    public function signInAsAdmin($user = null)
    {
        $this->signIn($user);

        $this->user->addRole('administrator');

        return $this;
    }

    public static function createPermissionedRole($permissionName)
    {
        if (!is_array($permissionName)) {
            $permissionName = [$permissionName];
        }

        $role = Role::create([
               'name'  => random_int(1000, 9999).'role_can_'.$permissionName[0],
        ]);

        foreach ($permissionName as $value) {
            $permission = Permission::firstOrCreate(['name' => $value],
                [ //If migrations haven't run still works
                    'name'         => $value,
                    'display_name' => $value,
                    'description'  => $value,
                ]);
            $role->attachPermission($permission);
        }

        return $role;
    }

    public static function getPermissionedUser($permissionName)
    {
        $role = static::createPermissionedRole($permissionName);
        $user = factory(App\User::class)->create();
        $user->attachRole($role);

        return $user;
    }

    public function signInAsPermissionedUser($permissionName)
    {
        $role = static::createPermissionedRole($permissionName);

        $this->signIn();

        $this->user->attachRole($role);

        return $this;
    }

    public function signInAsPermissionlessUser()
    {
        $this->signInAsPermissionedUser('role-with-no-permission');

        return $this;
    }

    public function signInAsRole($role)
    {
        if (!$this->user) {
            $this->signIn();
        }
        $this->user->addRole($role);

        return $this;
    }

    public function getAnUploadedFile($filename = 'test.png')
    {
        return new \Symfony\Component\HttpFoundation\File\UploadedFile(
            base_path("tests/unit/File/$filename"), $filename, $this->files[$filename]['type'], 12000, null, true
        );
    }

    /**
     * Assert that the client response has a given code.
     *
     * @param int $code
     *
     * @return $this
     */
    public function assertResponseStatus($code)
    {
        $actual = $this->response->getStatusCode();

        if ($actual == $code) {
            $this->assertEquals($actual, $code);

            return $this;
        }


        if ($actual != 200) {
            $message = "A request failed to get expected status code [$code]. Received status code [{$actual}].";

            $responseException = isset($this->response->exception)
                    ? $this->response->exception : null;

            if ($responseException instanceof \Illuminate\Validation\ValidationException) {
                $message .= response()->json($responseException->validator->getMessageBag(), 400);
            }

            throw new \Illuminate\Foundation\Testing\HttpException($message, null, $responseException);

            return $this;
        }


        PHPUnit_Framework_TestCase::assertEquals($code, $this->response->getStatusCode(), "Expected status code {$code}, got {$actual}. \nResponseContent:    ".$this->response->getContent());

        return $this;
    }

    ////////// APITestCase Workings

    /**
     * Posts a model with the given fields and checks that the status code matches.
     *
     * @param array  $fields        An array of fields to post
     * @param int    $code          The code expected
     * @param string $responseToSee An optional string to look for
     */
    public function storeFieldsGetSee($fieldsToPost, $expectedCode = 200, $responseToSee = null, $jsonFields = [])
    {
        $this->setUnsetDefaults();

        $this->contentToPost = $this->getPostArray($this->class, $fieldsToPost);

        $this->post($this->route, $this->contentToPost)
             ->assertResponseStatus($expectedCode);

        if ($expectedCode == 200) {
            $this->checkDatabaseFor($this->contentToPost, $jsonFields);
        }

        if ($responseToSee) {
            $this->seeInResponse($responseToSee);
        }

        return $this;
    }

    /**
     * Posts a model with the required fields merged with any content that user wants to submit.
     *
     * @param array $content An array of content to merge into a post
     * @param int   $code    The code expected
     */
    public function storeContentGetSee($contentToPost, $expectedCode = 200, $responseToSee = null, $jsonFields = [])
    {
        $this->setUnsetDefaults();

        $defaultPost = $this->getPostArray($this->class, $this->defaultFields);

        $this->contentToPost = array_merge($defaultPost, $contentToPost);

        $this->post($this->route, $this->removeNullValues($this->contentToPost))
                ->assertResponseStatus($expectedCode);

        if ($expectedCode == 200) {
            $this->checkDatabaseFor($contentToPost, $jsonFields);
        }

        if ($responseToSee) {
            $this->seeInResponse($responseToSee);
        }

        return $this;
    }

    /**
     * Makes a user and updates them with the fields asked for.
     *
     * @param array $fields An array of fields to post
     * @param int   $code   The code expected
     */
    public function updateFieldsGetSee($fieldsToPost, $expectedCode = 200, $responseToSee = null, $jsonFields = [])
    {
        $this->setUnsetDefaults();

        if (!$this->modelToUpdate) {
            $this->modelToUpdate = factory($this->class)->create();
        }

        $this->contentToPost = $this->getPostArray($this->class, $fieldsToPost);

        $id = $this->modelToUpdate->slug ?: $this->modelToUpdate->id;

        $this->patch($this->route.$id, $this->contentToPost)
                ->assertResponseStatus($expectedCode);


        if ($expectedCode == 200) {
            $this->checkDatabaseFor(
                array_merge(
                    ['id'    =>  $this->modelToUpdate->id],
                    $this->contentToPost
                ),
                $jsonFields
            );
        }

        if ($responseToSee) {
            $this->seeInResponse($responseToSee);
        }

        return $this;
    }

    /**
     * Makes a user then merges them in with any content that user wants to submit.
     *
     * @param array $content An array of content to merge into a post
     * @param int   $code    The code expected
     */
    public function updateContentGetSee($contentToPost, $expectedCode = 200, $responseToSee = null, $jsonFields = [])
    {
        $this->setUnsetDefaults();

        if (!$this->modelToUpdate) {
            $this->modelToUpdate = factory($this->class)->create();
        }

        $defaultPost = $this->getPostArray($this->class, $this->defaultFields);

        $this->contentToPost = array_merge($defaultPost, $contentToPost);

        $id = $this->modelToUpdate->slug ?: $this->modelToUpdate->id;

        $this->patch($this->route.$id, $this->removeNullValues($this->contentToPost))
                ->assertResponseStatus($expectedCode);

        $this->contentToPostcontentToPost['id'] = $this->modelToUpdate->id;

        if ($expectedCode == 200) {
            $this->checkDatabaseFor($this->contentToPost, $jsonFields);
        }

        if ($responseToSee) {
            $this->seeInResponse($responseToSee);
        }
    }

    /**
     * Gets a user post array with given fields.
     *
     * @param array $fields An array of fields
     *
     * @return array An array ready to post
     */
    public function getPostArray($class, $fields)
    {
        $post = factory($class)->make()->setVisible($fields)->toArray();

        // Restore always hidden fields
        foreach ($this->alwaysHidden as $hiddenField) {
            if (in_array($hiddenField, $fields)) {
                $post[$hiddenField] = 'abcd1234!'; //Usually (if not always) password
            }
        }


        foreach ($post as $postField => $postValue) {
            if (!in_array($postField, $fields)) {
                unset($post[$postField]);
            }
        }

        return $post;
    }

    /**
     * Gets an array without the values in the the values array.
     *
     * @param array $array Array of values
     * @param array $value Values to remove
     *
     * @return array Array without the values
     */
    public function getArrayWithoutValues($array, $values)
    {
        foreach ($values as $value) {
            unset($array[$value]);
        }

        return $array;
    }

    /**
     * Gets all the keys out of the array where the value is null.
     *
     * @param array $array Array candidate
     *
     * @return array Array with null values filtered out
     */
    public function removeNullValues($array)
    {
        return array_filter($array, function ($value) {
            return $value !== null;
        });
    }

    /**
     * Looks for any fields in the database and also checks JSON columns.
     *
     * @param array $contentPosted Array of key value pairs to check for
     * @param array $jsonFields    Keys of any fields that are JSON
     *
     * @return $this
     */
    public function checkDatabaseFor($contentPosted, $jsonFields)
    {
        $nonJsonFields = array_diff_key($contentPosted, array_flip($jsonFields));

        if (!empty($nonJsonFields)) {
            $this->seeInDatabase($this->table, $this->getArrayWithoutValues($nonJsonFields, $this->skipDatabaseCheck));
        }

        foreach ($jsonFields as $jsonField) {
            $query = $this->class::where('content->'.$jsonField, $contentPosted[$jsonField]);

            if (array_key_exists('id', $contentPosted)) {
                $query->where('id', $contentPosted['id']);
            }

            $record = $query->first();

            $this->assertNotEquals($record, null, "Unable to find JSON record for '$jsonField' in the database equal to '$contentPosted[$jsonField]'");
            $this->assertNotEquals($record->$jsonField, null);
        }

        return $this;
    }

    /**
     * Handling ocassionally flakey JSON response analysis.
     *
     * @param String/Array $responseToSee A string or array anticipated
     *
     * @return $this
     */
    public function seeInResponse($responseToSee)
    {
        if (!is_array($responseToSee)) {
            $this->see($responseToSee);

            return $this;
        }

        foreach ($responseToSee as $string) {
            $this->see($string);
        }

        return $this;
    }

    /**
     * Handling ocassionally flakey JSON response analysis.
     *
     * @param String/Array $responseToSee A string or array anticipated
     *
     * @return $this
     */
    public function dontSeeInResponse($responseToSee)
    {
        if (!is_array($responseToSee)) {
            $this->dontSee($responseToSee);

            return $this;
        }

        foreach ($responseToSee as $string) {
            $this->dontSee($string);
        }

        return $this;
    }

    /**
     * Guessing the things that people should be setting.
     */
    public function setUnsetDefaults()
    {
        if (!isset($this->alwaysHidden)) {
            $this->alwaysHidden = []; //Guessing if not set
        }

        if (!isset($this->table)) {
            $this->table = (new $this->class())->getTable();
        }

        if (!isset($this->skipDatabaseCheck)) {
            $this->skipDatabaseCheck = $this->alwaysHidden;
        }
    }

    /**
     * Assert that the JSON response has a given structure.
     *
     * @param int|0      $expected
     * @param array|null $responseData
     *
     * @return $this
     */
    public function seeNumberOfResults($expected = 0, $responseData = null)
    {
        if (!$responseData) {
            $responseData = json_decode($this->response->getContent(), true)['data'];
        }

        $this->assertEquals(count($responseData), $expected);

        return $this;
    }

    /**
     * Asserts that the JSON response has a given structure.
     *
     * @param string|desc $order
     * @param string|id   $fieldName
     *
     * @return $this
     */
    public function seeOrderInTimeField($order = 'desc', $fieldName = 'id', $responseData = null)
    {
        if (!$responseData) {
            $responseData = json_decode($this->response->getContent(), true)['data'];
        }

        $this->assertTrue(count($responseData) > 0);

        $previousClosingAt = Carbon::parse($responseData[0][$fieldName]['carbon']['date']);

        foreach ($responseData as $record) {
            $thisClosingAt = Carbon::parse($record[$fieldName]['carbon']['date']);

            if ($order == 'desc') {
                $this->assertTrue(
                    $thisClosingAt->lte($previousClosingAt)
                );
            } else {
                $this->assertTrue(
                    $thisClosingAt->gte($previousClosingAt)
                );
            }

            $previousClosingAt = $thisClosingAt;
        }
    }

    //Email Trapping & Testing

    public $mailerInstance;

    /**
     * @param array $emails
     *
     * @return MailThiefCollection
     */
    public function getMessagesFor(array $emails)
    {
        return $this->getMessages()->filter(function ($message) use ($emails) {
            foreach ($emails as $email) {
                if ($message->hasRecipient($email)) {
                    return true;
                }
            }

            return false;
        });
    }

    /**
     * @param string $email
     *
     * @return Message
     */
    public function getLastMessageFor(string $email)
    {
        return $this->getMessagesFor([$email])->last();
    }

    /**
     * @return MailThiefCollection
     */
    public function getMessages()
    {
        return $this->mailerInstance->messages;
    }
}
