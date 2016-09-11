<?php namespace App;

use Zizaco\Entrust\EntrustPermission;

use Cache;

class Permission extends EntrustPermission {

	protected $fillable = ['name','display_name','description'];


    public static function boot(){
        parent::boot();

        static::created(function($model){
            Cache::tags('role')->flush();
            return true;
        });

        static::updated(function($model){
            Cache::tags('role')->flush();
            return true;
        });
        
       

    }




	public static function makeSet($model){
		$model = new $model;

		$statusOptions = array_keys ($model::$statuses);
		if(!$statusOptions){
			abort(500,'The model "'.class_basename($model).'"" not have a status attribute setup');
		}

		return static::storeAndFetchSet(
			strtolower(
				class_basename($model)),
				$statusOptions
			);
	}


    public static function storeAndFetchSet($class,$stausOptions){
        $permissions = array_merge(
        	static::generateASet('Own',$class,$stausOptions),
        	static::generateASet('Others',$class,$stausOptions)
        );
        $permissionRecords = [];

        foreach($permissions as $permission){
            $permissionRecords[$permission['name']] = static::updateOrCreate(['name'=>$permission['name']],[
                'name'          =>  $permission['name'],
                'display_name'  =>  $permission['display_name'],
                'description'   =>  $permission['description']
            ]);
        }

        return $permissionRecords;
    }


    public static function generateASet($for=null,$class="Article",Array $stausOptions){
    	
    	$permissionStrings = [];
    	foreach($stausOptions as $stausOption){
    		// "Store Own Draft Article" or "Update Others Private User"
        	$permissionStrings[] = static::generateSingleStatus('Store',$for,$stausOption,$class);
            $permissionStrings[] = static::generateSingleStatus('Show',$for,$stausOption,$class);
            $permissionStrings[] = static::generateSingleStatus('Update',$for,$stausOption,$class);
            $permissionStrings[] = static::generateSingleStatus('Destroy',$for,$stausOption,$class);
    	}

    	return $permissionStrings;
    }

    /**
	 * This is a summary
	 *
	 * This is a description
	 */

    public static function generateSingleStatus(string $action="Store",string $for=null,string $status="Draft",string $resource="Article"){

        if($for){
            return [
                'name'          =>  strtolower($action).'-'.strtolower($for).'-'.strtolower($status).'-'.strtolower($resource),
                'display_name'  =>  "$action $resource",
                'description'   =>  "Able to $action ".$resource."s under ".$for." name"
            ];
        } else {
            return [
                'name'          =>  strtolower($action).'-'.strtolower($status).'-'.strtolower($resource),
                'display_name'  =>  "$action $resource",
                'description'   =>  "Able to Store ".$resource."s under ".$for." name"
            ];
        }

    }

    public static function getAllIdsFor($permissions){
        $ids = [];

        foreach($permissions as $permission){
            $ids[] = $permission->id;
        }

        return $ids;
    }


    public static function generatePermissionText($action,$for,$status,$resource){
       return strtolower($action).'-'.strtolower($for).'-'.strtolower($status).'-'.strtolower($resource);
    }

}
