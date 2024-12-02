<?php

namespace App\Filament\Resources;

use App\Actions\ExtractShapefileFromZip;
use App\Actions\GenerateSqlFromExtractedShapefileFolder;
use App\Actions\ImportShapeFileSql;
use App\Filament\Resources\ShapefileZipResource\Pages;
use App\Filament\Resources\ShapefileZipResource\RelationManagers;
use App\Models\ShapefileZip;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShapefileZipResource extends Resource
{
    protected static ?string $model = ShapefileZip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('file_name')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                Forms\Components\FileUpload::make('file_path')
                    ->disk('local')
                    ->label('File')
                    ->directory(fn(Forms\Get $get)=>filled($get('file_name')) ? str($get('file_name'))->snake()->value() : 'zips')
                    ->rules(['required', 'file','mimes:zip'])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('file_path')
                    ->searchable(),
                Tables\Columns\TextColumn::make('extracted_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('converted_to_sql_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sql_imported_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('extractZip')
                    ->requiresConfirmation()
                    ->action(function (ShapefileZip $record,Tables\Actions\Action $action) {
                        ExtractShapefileFromZip::execute($record);
                    })->visible(fn(Model $record): bool => blank($record->extracted_at) && filled($record->file_path)),

                Tables\Actions\Action::make('generateSql')
                    ->requiresConfirmation()
                    ->action(function (ShapefileZip $record,Tables\Actions\Action $action) {
                        GenerateSqlFromExtractedShapefileFolder::execute($record);
                    })->visible(fn(Model $record): bool => blank($record->converted_to_sql_at) && filled($record->extracted_at) && filled($record->file_path)),

                Tables\Actions\Action::make('importSql')
                    ->action(function (ShapefileZip $record,Tables\Actions\Action $action) {
                        ImportShapeFileSql::execute($record);
                    })->visible(fn(Model $record): bool => blank($record->sql_imported_at) && filled($record->file_path) && filled($record->converted_to_sql_at))
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShapefileZips::route('/'),
            'create' => Pages\CreateShapefileZip::route('/create'),
            'edit' => Pages\EditShapefileZip::route('/{record}/edit'),
        ];
    }
}
