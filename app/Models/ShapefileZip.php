<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShapefileZip extends Model
{
    protected $fillable = [
        'file_name',
        'file_path',
        'extracted_at',
        'converted_to_sql_at',
        'sql_imported_at',
    ];

    protected $casts =[
        'extracted_at' => 'timestamp',
        'converted_to_sql_at' => 'timestamp',
        'sql_imported_at' => 'timestamp',
    ];


    public function cleanUp()
    {
        //delete folder
    }


    protected static function booted(): void
    {
//        static::created(function (ShapefileZip $zip) {
//
//            if($zip->wasRecentlyCreated){
//
//                if($zip->file_path){
//
//                    $zip->updateQuietly([
//                        ''
//                    ]);
//                }
//            }
//        });
    }


}
