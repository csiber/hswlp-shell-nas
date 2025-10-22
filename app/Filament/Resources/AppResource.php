<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppResource\Pages;
use App\Models\App;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AppResource extends Resource
{
    protected static ?string $model = App::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $modelLabel = 'Alkalmazás';

    protected static ?string $navigationGroup = 'Alkalmazások';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('id')
                    ->label('Azonosító')
                    ->required()
                    ->alphaDash()
                    ->maxLength(50)
                    ->disabledOn('edit'),
                TextInput::make('title')
                    ->label('Megjelenített név')
                    ->required()
                    ->maxLength(150),
                Select::make('status')
                    ->label('Állapot')
                    ->options([
                        'installed' => 'Telepítve',
                        'running' => 'Fut',
                        'stopped' => 'Leállítva',
                    ])
                    ->required(),
                TextInput::make('http_port')
                    ->label('HTTP port')
                    ->numeric()
                    ->minValue(1)
                    ->maxValue(65535)
                    ->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Név')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('id')
                    ->label('Azonosító')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Állapot')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'installed' => 'Telepítve',
                        'running' => 'Fut',
                        'stopped' => 'Leállítva',
                        default => $state,
                    }),
                TextColumn::make('http_port')
                    ->label('HTTP port')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ?: '—'),
                TextColumn::make('instances_count')
                    ->label('Példányok')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Utolsó módosítás')
                    ->dateTime('Y.m.d. H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Állapot')
                    ->options([
                        'installed' => 'Telepítve',
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('instances');
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
            'index' => Pages\ListApps::route('/'),
            'create' => Pages\CreateApp::route('/create'),
            'edit' => Pages\EditApp::route('/{record}/edit'),
        ];
    }
}
