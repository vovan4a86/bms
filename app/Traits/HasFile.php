<?php namespace App\Traits;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile ;
use Image;
use Settings;
use Thumb;

/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 19.12.2017
 * Time: 11:09
 */


trait HasFile{
	public $file_field = 'file';

	public function deleteSrcFile($upload_url = null) {
		if(!$this->{$this->file_field}) return;
	
		if(!$upload_url){
			$upload_url = self::UPLOAD_URL;
		}

		@unlink(public_path($upload_url . $this->{$this->file_field}));
	}

	public function getFileSrcAttribute() {
		return $this->{$this->file_field} ? url(self::UPLOAD_URL . $this->{$this->file_field}) : null;
	}

    /**
     * Converts bytes into human readable file size.
     *
     * @param string $bytes
     * @return string human readable file size (2,87 Мб)
     * @author Mogilev Arseny
     */
    public function fileSizeConvert($bytes) {
        $bytes = floatval($bytes);
        $arBytes = array(
            0 => array(
                "UNIT" => "тб",
                "VALUE" => pow(1024, 4)
            ),
            1 => array(
                "UNIT" => "гб",
                "VALUE" => pow(1024, 3)
            ),
            2 => array(
                "UNIT" => "мб",
                "VALUE" => pow(1024, 2)
            ),
            3 => array(
                "UNIT" => "кб",
                "VALUE" => 1024
            ),
            4 => array(
                "UNIT" => "б",
                "VALUE" => 1
            ),
        );

        foreach($arBytes as $arItem)
        {
            if($bytes >= $arItem["VALUE"])
            {
                $result = $bytes / $arItem["VALUE"];
                $result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
                break;
            }
        }
        return $result;
    }


    public function getFileSizeAttribute() {
        $size = filesize(public_path(self::UPLOAD_URL . $this->{$this->file_field}));
        return $this->fileSizeConvert($size);
    }
	/**
	 * @param UploadedFile $file
	 * @return string
	 */
	public static function uploadFile(UploadedFile $file): string {
		$file_name = md5(uniqid(rand(), true)) . '_' . time() . '.' . Str::lower($file->getClientOriginalExtension());
        $file->move(public_path(self::UPLOAD_URL), $file_name);
		return $file_name;
	}
}
