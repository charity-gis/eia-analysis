<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use Illuminate\Support\Facades\DB;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/jeo', function (Request $request) {
    $postings = DB::table('test2')
        ->select([
            'area',
            'forest',
            'gazetted',
            DB::raw('ST_AsGeoJSON(geom) as geojson')
        ])
        ->get();

    // Convert the result to a GeoJSON FeatureCollection
    $features = $postings->map(function ($posting) {
        return [
            'type' => 'Feature',
            'geometry' => json_decode($posting->geojson), // Decode GeoJSON
            'properties' => [
                'id' => $posting->id,
                'title' => $posting->title,
                'description' => $posting->description
            ]
        ];
    });

    $geoJson = [
        'type' => 'FeatureCollection',
        'features' => $features
    ];

    // Return as JSON response
    return response()->json($geoJson, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

});


