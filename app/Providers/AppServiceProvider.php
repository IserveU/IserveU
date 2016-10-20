<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (config('app.debug')) {
            \DB::listen(function ($sql) {
                foreach ($sql->bindings as $i => $binding) {
                    if ($binding instanceof \DateTime) {
                        $sql->bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                    } else {
                        if (is_string($binding)) {
                            $sql->bindings[$i] = "'$binding'";
                        }
                    }
                }

                // Insert bindings into query
                $query = str_replace(['%', '?'], ['%%', '%s'], $sql->sql);

                $query = vsprintf($query, $sql->bindings);

                // Save the query to file
                $logFile = fopen(
                    storage_path('logs'.DIRECTORY_SEPARATOR.date('Y-m-d').'_query.log'),
                    'a+'
                );
                fwrite($logFile, date('Y-m-d H:i:s').': '.$query.PHP_EOL);
                fclose($logFile);
            });
        }


        Validator::replacer('valid_status', function ($message, $attribute, $rule, $parameters) {
            return $message.' Invalid status. The current time on the server is '.\Carbon\Carbon::now();
        });

        Validator::extend('valid_status', function ($attribute, $value, $parameters, $validator) {
            $data = $validator->getData();

            $status = $data[$attribute];

            if (!in_array($status, ['draft', 'submitted', 'review', 'closed', 'published', 'public', 'private'], true)) {
                return false; //Not a valid status
            }


            if (!array_key_exists('published_at', $data)) {
                return true; //don't case a problem if it doesn't exist
            }

            $published_at = $data['published_at'];

                //Get the date
                if (!($published_at instanceof \Carbon\Carbon)) {
                    $published_at = new \Carbon\Carbon($published_at);
                }

                // You cant set something as "published" and set the published date in the future
                if ($status == 'published' && $published_at > \Carbon\Carbon::now()) {
                    return false;
                }

                // You can set something as "scheduled" and have the published date in the past
                if ($status == 'scheduled' && $published_at < \Carbon\Carbon::now()) {
                    return false;
                }

            return true;
        });


        Validator::replacer('reject', function ($message, $attribute, $rule, $parameters) {
            return 'Field not allowed';
        });

        Validator::extend('reject', function ($attribute, $value, $parameters, $validator) {
            return false;
        });

        Validator::extend('notRequired', 'CustomValidation@notRequired');
    }

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'Illuminate\Contracts\Auth\Registrar',
            'App\Services\Registrar'
        );
    }
}
