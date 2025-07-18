<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CrowdfundingResource\Pages;
use App\Models\Crowdfunding_campaign;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CrowdfundingResource extends Resource
{
    protected static ?string $model = Crowdfunding_campaign::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Crowdfunding Campaigns';
    protected static ?string $pluralModelLabel = 'Crowdfunding Campaigns';
    protected static ?string $slug = 'crowdfunding-campaigns';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                   Forms\Components\TextInput::make('title')
                    ->label('Title')
                    ->required()
                    ->maxLength(100),

                Forms\Components\Textarea::make('description')
                    ->rows(4)
                    ->required(),

                Forms\Components\TextInput::make('target_amount')
                    ->label('Target Dana (Rp)')
                    ->required()
                    ->numeric()
                    ->prefix('Rp'),

                Forms\Components\DatePicker::make('deadline')
                    ->label('Deadline')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'active' => 'Active',
                        'completed' => 'Completed',
                        'inactive' => 'Inactive',
                    ])
                    ->required()
                    ->default('active'),

                Forms\Components\FileUpload::make('photo_url')
                    ->label('Banner / Thumbnail')
                    ->image()
                    ->directory('crowdfunding-images')
                    ->disk('public') // <== tambahkan ini juga
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('goal_amount')
                    ->label('Target Dana')
                    ->money('IDR'),

                Tables\Columns\TextColumn::make('deadline')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'primary' => 'active',
                        'success' => 'completed',
                        'gray' => 'inactive',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i'),
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
            'index' => Pages\ListCrowdfundings::route('/'),
            'create' => Pages\CreateCrowdfunding::route('/create'),
            'edit' => Pages\EditCrowdfunding::route('/{record}/edit'),
        ];
    }
}
