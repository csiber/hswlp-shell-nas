<?php

namespace App\Filament\Resources\ShareResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $recordTitleAttribute = 'user.name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Felhasználó')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('perm')
                    ->label('Jogosultság')
                    ->options([
                        'read' => 'Olvasás',
                        'write' => 'Írás',
                        'admin' => 'Admin',
                    ])
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Felhasználó')
                    ->searchable(),
                TextColumn::make('perm')
                    ->label('Jogosultság')
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'read' => 'Olvasás',
                        'write' => 'Írás',
                        'admin' => 'Admin',
                        default => $state,
                    }),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Új jogosultság'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Szerkesztés'),
                Tables\Actions\DeleteAction::make()->label('Törlés'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
