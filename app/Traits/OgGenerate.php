<?php namespace App\Traits;
use Illuminate\Support\Str;
use Image;
use OpenGraph;
use Settings;
use Thumb;

/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 19.12.2017
 * Time: 11:09
 */


trait OgGenerate{
	public function ogGenerate() {
		OpenGraph::setUrl($this->url);
		if($this->og_title){
			OpenGraph::setTitle($this->og_title);
		}
		if($this->og_description){
			OpenGraph::setDescription($this->og_description);
		}
		if($this->image_src){
			OpenGraph::addImage($this->image_src);
		}
	}
}