<?php

namespace App\Repositories;
use Flow\Config as FlowConfig;
use Flow\Request as FlowRequest;
use Flow\Basic as FlowBasic;
use Flow\File as FlowFile;
use Illuminate\Support\Facades\Input;

use Illuminate\Http\Request;

use Storage;
use Carbon\Carbon;

use SplFileInfo;

/* This Class helps you use Flow and regular file uploads */
class FileUploadHelper{


    /**
     * The name of the file in storage
     * @var String
     */
    protected $fileName;


    /**
     * The route to place the file in storage
     * @var String
     */
    protected $folder = "";


    /**
     * The field in the request which holds the file
     * @var String
     */
    protected $fileField;


    /**
     * If the file is ready and in storage
     * @var boolean
     */
    protected $fileReady = false;


    /**
     * If this is a flow file request
     * @var boolean
     */
    protected $flowFileRequest = false;

    /**
     * Chunk information for responses
     * @var string
     */
    protected $chunk;


    /**
     * Create a new instance.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        foreach($attributes as $key => $value){
            $this->$key = $value;    
        }
    }


    /**
     * Creates a new instance of the file upload helper with a request
     * @param  Request $request    The request containing the file data
     * @param  array   $attributes An array of attributes to set
     * @return $this
     */
    public static function create(Request $request,array $attributes){
        $instance = new static($attributes);

        $instance->flowFileRequest = $request->has('flowFilename');

        if($instance->flowFileRequest){
            $instance->handleFlowRequest($request);
        } else {
            $instance->handleRegularRequest($request);
        }

        return $instance;
    }

    /**
     * If a file is ready and sitting in storage
     * @return [type] [description]
     */
    public function fileReady(){
        if(!$this->fileReady){

            return false;
        }

        if(!$this->fileName){
            return false;
        }

        return true;
    }

    /**
     * Returns the name of the file saved
     * @return [type] [description]
     */
    public function getFileName(){
        return $this->fileName;
    }


    /**
     * Returns the status of the upload or chunk
     * @return Json string with status that can be piped to front end
     */
    public function getStatus(){
        if($this->fileReady()){
            return ['status' => 'File uploaded to storage/'.$this->folder." as ".$this->fileName];
        }

        if($this->flowFileRequest){
            //$fileChunk->checkChunk();
            return ['status' => 'Chunk '.$this->chunk->getIdentifier()." uploaded"];
        }

        return ['status' => 'Failed to upload regular request'];
    }



    /**
     * Handles a regular file upload, moves the file into storage
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function handleRegularRequest(Request $request){
        if(!$file =  $request->file){
            return false;
        }
        

        $this->fileName = static::nameFile($file->getClientOriginalName());

        Storage::put($this->folder."/".$this->fileName ,\File::get($file));
       
        $this->fileReady = true;
    }

  
    /**
     * Takes a flow file request
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function handleFlowRequest(Request $request){
        $this->createFlowFile();

        if(!$this->fileName) return null;

        $this->fileReady = true;
    }
    
    /**
     * Save a File and create File Attribute for File Model.
     *
     * @return null
     */
    public function createFlowFile(){
    	$this->chunk = $this->createChunk();
      
    	$this->saveAndMergeChunks($this->chunk);
    }

    /**
     * Create file chunks.
     *
     * @return \Flow\File $fileChunk
     */  
    public function createChunk()
    {
    	//create tmp directory to store file chunks. 
        if(!file_exists(storage_path('app/flowtmp'))) {
            \File::makeDirectory(storage_path('app/flowtmp'));
        }

        //create configurations and request for file chunks.  
        $config = new FlowConfig(array(
            'tempDir' => storage_path('app/flowtmp')
        ));

        $flowRequest = new FlowRequest();
        return $fileChunk = new FlowFile($config, $flowRequest);
    }

    /**
     * Save and merge all chunks.
     *
     * @param  \Flow\File $fileChunk, StoreFileRequest $request
     */
    public function saveAndMergeChunks($fileChunk){
        
        // If POST is called, VALIDATE and SAVE the posted chunk
        if(!$fileChunk->validateChunk()) abort(400,"Invalid chunk upload request");

        $fileChunk->saveChunk();

        //verify if all the file chunks has been uploaded, then save the file to Storage. 
        if(!$fileChunk->validateFile()){
            return "Waiting on more chunks";
        }

    	$fileName              =   static::nameFile(Input::get('flowFilename'));
    	$totalFileSize         =   Input::get('flowTotalSize');

        $destinationPath = ($this->folder)? storage_path('app/'.$this->folder.'/') : storage_path('app/');

    	$fileChunk->save($destinationPath.$fileName);
        $this->fileName        =   $fileName;
      
    }

    public static function nameFile(string $fileName){
        $timestamp      = Carbon::now()->timestamp;
        $fileName       = new SplFileInfo($fileName);
        return $timestamp."_".md5($fileName)."_".str_random(10).".".$fileName->getExtension();
    }

}