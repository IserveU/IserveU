<?php

namespace App\Console\Commands;

use App\User;
use Maatwebsite\Excel\Collections\CellCollection;
use Maatwebsite\Excel\Collections\RowCollection;
use Excel;
use Illuminate\Console\Command;

class ProcessCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:csv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process a CSV file sitting in storage';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

      Excel::load(storage_path('app/nationbuilder.csv'), function($reader) {

          $results = static::filterUnusableRecords($reader->get());


            foreach($results as $result){
              try{
                static::processUser($result);
              } catch (Exception $e){
                  dd($e);
              }
            }

      });
    }

    public static function filterUnusableRecords(RowCollection $collection){

      return $collection->filter(function($filter){
          if(empty($filter->email) && empty($filter->email1) &&  empty($filter->email2)) return false;
          if(empty($filter->first_name) && empty($filter->last_name)) return false;
          if($filter->signup_type) return false; //Seems to be the org field
          return true;
      });

    }

    public static function processUser(CellCollection $result){

      $user = User::whereIn('email',[$result->email,$result->email1,$result->email2])->first();

      $address = array_values(array_filter([$result->primary_address1,$result->user_submitted_submitted_address,$result->user_submitted_address1], function($val){
                    return !empty($val);
                 }));

      $address = empty($address)?null:$address[0];

      if($user){
        if(!$user->first_name && $result->first_name) $user->first_name = $result->first_name;
        if(!$user->middle_name && $result->middle_name) $user->middle_name = $result->middle_name;
        if(!$user->last_name && $result->last_name) $user->last_name = $result->last_name;
        if(!$user->postal_code && $result->primary_zip) $user->postal_code = $result->primary_zip;
        if(!$user->street_name && $address) $user->street_name = $address;
        if(!$user->community_id && ($result->primary_city=="Yellowknife")) $user->community_id = 1;

        $user->save();
        return $user;
      }

      $email =  array_values(array_filter([$result->email,$result->email1,$result->email2], function($val){
                  return !empty($val);
                }))[0];

      $unwanted_array = array('Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
      'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
      'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
      'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
      'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', ','=>'.');

      $email = strtolower(strtr( $email, $unwanted_array ));

      if(\Validator::make(['email'=>$email],[
          'email' =>  'email|required'
      ])->fails()) return false;


      return User::create([
        "email"           =>  $email,
        "first_name"      =>  $result->first_name??"Unknown",
        "middle_name"     =>  $result->middle_name,
        "last_name"       =>  $result->last_name??"Unknown",
        "postal_code"     =>  $result->primary_zip,
        "street_name"     =>  $address,
        "community_id"    =>  (strpos($result->primary_city, "Yellow") !== FALSE)?1:0 //($result->primary_city=="Yellowknife")?1:null,
      ]);



    }
}
