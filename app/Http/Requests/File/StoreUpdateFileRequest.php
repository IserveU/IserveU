<?php

namespace App\Http\Requests\File;

use App\Http\Requests\Request;

class StoreUpdateFileRequest extends Request
{
  /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'                     =>      'string',
            'description'               =>      'string',
            'user_id'                   =>      'reject', //Detected and set by code
            'replacement_id'            =>      'reject', //Detected and set by code
            'fileable_id'               =>      'reject', //Detected and set by code
            'fileable_type'             =>      'reject', //Detected and set by code
            'filename'                  =>      'reject', //Detected and set by code
            'file'                      =>      'file',
            'flowChunkNumber'           =>      'string',
            'flowChunkSize'             =>      'string',
            'flowCurrentChunkSize'      =>      'string',
            'flowTotalSize'             =>      'string',
            'flowIdentifier'            =>      'string',
            'flowFilename'              =>      'string',
            'flowRelativePath'          =>      'string',
            'flowTotalChunks'           =>      'string'
        ];
    }
}
