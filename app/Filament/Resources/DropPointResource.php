<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DropPointResource\Pages;
use App\Models\Drop_point;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DropPointResource extends Resource
{
    protected static ?string $model = Drop_point::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Drop Points';
    protected static ?string $pluralModelLabel = 'Drop Points';
    protected static ?string $slug = 'drop-points';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                   Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(100),

                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('address')
                    ->label('Address')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('latitude')
                    ->label('Latitude')
                    ->required()
                    ->numeric(),

                Forms\Components\TextInput::make('longitude')
                    ->label('Longitude')
                    ->required()
                    ->numeric(),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3)
                    ->maxLength(500)
                    ->nullable(),

                Forms\Components\Select::make('admin_id')
                    ->label('Admin')
                    ->relationship('admin', 'name')
                    ->required(),

                Forms\Components\FileUpload::make('photo_url')
                     ->label('Photo')
                     ->image()
                     ->directory('drop-point-photos')
                     ->visibility('public')
                     ->nullable()
                     ->columnSpan('full'),


                    

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),

                Tables\Columns\TextColumn::make('address')
                    ->label('Address')
                    ->limit(50),

                Tables\Columns\TextColumn::make('latitude')
                    ->label('Lat'),

                Tables\Columns\TextColumn::make('longitude')
                    ->label('Lng'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDropPoints::route('/'),
            'create' => Pages\CreateDropPoint::route('/create'),
            'edit' => Pages\EditDropPoint::route('/{record}/edit'),
        ];
    }
}
