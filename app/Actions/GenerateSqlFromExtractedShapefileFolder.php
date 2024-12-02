<?php

namespace App\Actions;

use App\Models\ShapefileZip;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Process;
use Illuminate\Process\Pipe;
use Symfony\Component\Process\Process as PhpProcess;

class GenerateSqlFromExtractedShapefileFolder
{

    /****
     * @param ShapefileZip $zip
     * @return void
     *
     *
     *
     */

    public static function execute(ShapefileZip $zip){

        $path = str($zip->file_name)->snake()->value();

        $files = Storage::disk('local')->files($path);

        $shapeFile = collect($files)->filter(function($file){
            return Str::endsWith($file, '.shp');
        });

        $shape_file_name = $shapeFile->first();

        $shape_file_name = Storage::disk('local')->path($shape_file_name);

        $table_name = Str::of($zip->file_name)->snake()->value();

        $snake_sql = str($table_name)->value();

        $command = "shp2pgsql -s 4326 $shape_file_name public.$table_name";

        $result = Process::path('C:\Program Files\PostgreSQL\17\bin')
        ->command($command)
        ->run();

        if($result->successful()){

            //save the sql file

            Storage::disk('local')->put("$path/$snake_sql.sql",$result->output());

            $zip->forceFill([
                'converted_to_sql_at' => now(),
            ])->saveQuietly();

        }else{

            Notification::make()->title("Error Generating Sql from  $zip->file_name")->warning()->body($result->errorOutput())->send();

        }
    }

}
