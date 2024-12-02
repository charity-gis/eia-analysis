<?php

namespace App\Actions;

use App\Models\ShapefileZip;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\Storage;

use Symfony\Component\Process\Process;

class ImportShapeFileSql
{

    /***
     * @param ShapefileZip $file
     * @return void
     *
     *
     *
     */


    public static function execute(ShapefileZip $file)
    {

        $path =  str($file->file_name)->lower()->snake();

        $sql = Storage::disk('local')->path($path->value().'/'.$path->append('.sql')->value());

        $sql = str_replace('\\', '/', $sql);

        $psqlBinaryDir = 'C:\\Program Files\\PostgreSQL\\17\\bin\\psql.exe';

        $escapedPsqlBinary = escapeshellarg($psqlBinaryDir);

        $command = "PGPASSWORD=postgres $escapedPsqlBinary -h 127.0.0.1 -p 5432 -U postgres -d postgis_db -f $sql";

        $gitBashPath = 'C:\\Program Files\\Git\\bin\\bash.exe';

        $process =  new Process([
           $gitBashPath ,
            '-c',
            $command
        ]);

        $process->setWorkingDirectory(__DIR__);


        $process->run();

        try {

            if ($process->isSuccessful()) {

                $file->forceFill([
                    'sql_imported_at'=>now()
                ])->saveQuietly();

                $file->cleanUp();
            }

        }catch (\Exception $exception){

            Notification::make()->title("Error Generating Sql from  $file->file_name")->warning()->body($exception->getMessage())->send();


        }

    }

}
