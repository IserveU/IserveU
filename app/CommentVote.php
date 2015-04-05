<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class CommentVote extends Model {

	public function vote(){	
		return $this->belongsTo('App\Vote');
	}

	public function comment(){	
		return $this->belongsTo('App\Comment');
	}


}
