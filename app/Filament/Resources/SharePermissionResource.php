<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SharePermissionResource\Pages;
use App\Models\SharePermission;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SharePermissionResource extends Resource
{
    protected static ?string $model = SharePermission::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $modelLabel = 'Megosztási jogosultság';

    protected static ?string $navigationGroup = 'Tárolás';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('share_id')
                    ->label('Megosztás')
                    ->relationship('share', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit'),
                Select::make('user_id')
                    ->label('Felhasználó')
                    ->relationship('user', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->disabledOn('edit'),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('share.name')
                    ->label('Megosztás')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Felhasználó')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('perm')
                    ->label('Jogosultság')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'read' => 'Olvasás',
                        'write' => 'Írás',
                        'admin' => 'Admin',
                        default => $state,
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('share')
                    ->label('Megosztás')
                    ->relationship('share', 'name'),
                Tables\Filters\SelectFilter::make('user')
                    ->label('Felhasználó')
                    ->relationship('user', 'name'),
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
            'index' => Pages\ListSharePermissions::route('/'),
            'create' => Pages\CreateSharePermission::route('/create'),
            'edit' => Pages\EditSharePermission::route('/{record}/edit'),
        ];
    }
}
