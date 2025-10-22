<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShareResource\Pages;
use App\Filament\Resources\ShareResource\RelationManagers\PermissionsRelationManager;
use App\Models\Share;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ShareResource extends Resource
{
    protected static ?string $model = Share::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';

    protected static ?string $modelLabel = 'Megosztás';

    protected static ?string $navigationGroup = 'Tárolás';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Általános adatok')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Megosztás neve')
                            ->required()
                            ->maxLength(100)
                            ->unique(ignoreRecord: true),
                        TextInput::make('path')
                            ->label('Elérési út')
                            ->required()
                            ->maxLength(255),
                        Select::make('owner_user_id')
                            ->label('Tulajdonos')
                            ->relationship('owner', 'name')
                            ->searchable()
                            ->preload()
                            ->nullable(),
                        TextInput::make('quota_gb')
                            ->label('Kvóta')
                            ->numeric()
                            ->minValue(1)
                            ->suffix('GB')
                            ->nullable(),
                    ]),
                Section::make('Szolgáltatások')
                    ->columns(2)
                    ->schema([
                        Toggle::make('smb_export')
                            ->label('SMB megosztás')
                            ->default(true),
                        Toggle::make('nfs_export')
                            ->label('NFS megosztás')
                            ->default(false),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Név')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('path')
                    ->label('Elérési út')
                    ->limit(40)
                    ->tooltip(fn (Share $record) => $record->path),
                IconColumn::make('smb_export')
                    ->label('SMB')
                    ->boolean(),
                IconColumn::make('nfs_export')
                    ->label('NFS')
                    ->boolean(),
                TextColumn::make('quota_gb')
                    ->label('Kvóta (GB)')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => $state ? $state.' GB' : '—'),
                TextColumn::make('owner.name')
                    ->label('Tulajdonos')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->since()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('owner')
                    ->label('Tulajdonos')
                    ->relationship('owner', 'name'),
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
            PermissionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShares::route('/'),
            'create' => Pages\CreateShare::route('/create'),
            'edit' => Pages\EditShare::route('/{record}/edit'),
        ];
    }
}
