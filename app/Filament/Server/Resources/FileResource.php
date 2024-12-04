<?php

namespace App\Filament\Server\Resources;

use App\Filament\Server\Resources\FileResource\Pages;
use App\Models\File;
use App\Models\Permission;
use App\Models\Server;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Model;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'tabler-files';

    // TODO: find better way handle server conflict state
    public static function canAccess(): bool
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        if ($server->isInConflictState()) {
            return false;
        }

        return parent::canAccess();
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->can(Permission::ACTION_FILE_READ, Filament::getTenant());
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can(Permission::ACTION_FILE_CREATE, Filament::getTenant());
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_FILE_UPDATE, Filament::getTenant());
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can(Permission::ACTION_FILE_DELETE, Filament::getTenant());
    }

    public static function getPages(): array
    {
        return [
            'edit' => Pages\EditFiles::route('/edit/{path}'),
            'search' => Pages\SearchFiles::route('/search/{searchTerm}'), // TODO: find better way?
            'index' => Pages\ListFiles::route('/{path?}'),
        ];
    }
}
