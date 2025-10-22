<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppInstanceResource\Pages;
use App\Models\AppInstance;
use Filament\Forms;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AppInstanceResource extends Resource
{
    protected static ?string $model = AppInstance::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $modelLabel = 'Alkalmazás példány';

    protected static ?string $navigationGroup = 'Alkalmazások';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('app_id')
                    ->label('Alkalmazás')
                    ->relationship('app', 'title')
                    ->required()
                    ->searchable()
                    ->preload(),
                TextInput::make('name')
                    ->label('Példány neve')
                    ->required()
                    ->maxLength(100),
                TextInput::make('bind_path')
                    ->label('Kötési útvonal')
                    ->required()
                    ->maxLength(255),
                Select::make('status')
                    ->label('Állapot')
                    ->options([
                        'running' => 'Fut',
                        'stopped' => 'Leállítva',
                    ])
                    ->required(),
                KeyValue::make('env_json')
                    ->label('Környezeti változók')
                    ->addButtonLabel('Új változó')
                    ->keyLabel('Kulcs')
                    ->valueLabel('Érték')
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('app.title')
                    ->label('Alkalmazás')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Példány neve')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('bind_path')
                    ->label('Kötési útvonal')
                    ->limit(40)
                    ->tooltip(fn (AppInstance $record) => $record->bind_path),
                TextColumn::make('status')
                    ->label('Állapot')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'running' => 'Fut',
                        'stopped' => 'Leállítva',
                        default => $state,
                    }),
                TextColumn::make('updated_at')
                    ->label('Utolsó módosítás')
                    ->dateTime('Y.m.d. H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('app')
                    ->label('Alkalmazás')
                    ->relationship('app', 'title'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Állapot')
                    ->options([
                        'running' => 'Fut',
                        'stopped' => 'Leállítva',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListAppInstances::route('/'),
            'create' => Pages\CreateAppInstance::route('/create'),
            'edit' => Pages\EditAppInstance::route('/{record}/edit'),
        ];
    }
}
