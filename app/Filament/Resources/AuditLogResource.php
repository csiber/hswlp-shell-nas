<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = 'Audit napló';

    protected static ?string $navigationGroup = 'Megfigyelés';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('action')
                    ->label('Művelet')
                    ->disabled(),
                TextInput::make('user.name')
                    ->label('Felhasználó')
                    ->disabled(),
                Textarea::make('payload_json')
                    ->label('Részletek')
                    ->rows(8)
                    ->disabled(),
                TextInput::make('created_at')
                    ->label('Időpont')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Időpont')
                    ->dateTime('Y.m.d. H:i:s')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.name')
                    ->label('Felhasználó')
                    ->sortable()
                    ->searchable()
                    ->placeholder('Rendszer'),
                TextColumn::make('action')
                    ->label('Művelet')
                    ->searchable(),
                TextColumn::make('payload_json')
                    ->label('Részletek')
                    ->limit(80)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('action')
                    ->label('Művelet')
                    ->options(fn () => AuditLog::query()
                        ->distinct()
                        ->orderBy('action')
                        ->pluck('action', 'action')
                        ->toArray()),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->label('Megtekintés'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
        ];
    }
}
