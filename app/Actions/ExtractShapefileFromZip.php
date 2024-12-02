<?php

namespace App\Actions;

use App\Models\ShapefileZip;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
class ExtractShapefileFromZip
{
    public static function execute(ShapefileZip $shapefileZip){


        // Create a new instance of ZipArchive
        $zip = new ZipArchive();


        try {
            $zip->open(Storage::disk('local')->path($shapefileZip->file_path));


            $zip->extractTo(
                storage_path("app/private/".str($shapefileZip->file_name)->snake()->value()),
            );
            // Close the ZIP file
            $zip->close();

            $shapefileZip->forceFill([
                'extracted_at'=>now()
            ])->saveQuietly();

        }catch (\Exception $e){

            Notification::make()->title("Error Extracting Files from  $shapefileZip->file_name.zip")->warning()->body($e->getMessage())->send();

        }
    }

}
