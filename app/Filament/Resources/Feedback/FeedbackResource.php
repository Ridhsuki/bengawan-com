<?php

namespace App\Filament\Resources\Feedback;

use App\Enums\FeedbackStatus;
use App\Filament\Resources\Feedback\Pages\ListFeedback;
use App\Filament\Resources\Feedback\Schemas\FeedbackInfolist;
use App\Filament\Resources\Feedback\Tables\FeedbackTable;
use App\Models\Feedback;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FeedbackResource extends Resource
{
    protected static ?string $model = Feedback::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFaceSmile;
    protected static string|\UnitEnum|null $navigationGroup = 'Inquiries';
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('status', FeedbackStatus::NEW)->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        $count = static::getModel()::where('status', FeedbackStatus::NEW)->count();

        return match (true) {
            $count > 10 => 'danger',
            $count > 5 => 'warning',
            default => 'primary',
        };
    }
    public static function table(Table $table): Table
    {
        return FeedbackTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return FeedbackInfolist::configure($schema);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListFeedback::route('/'),
        ];
    }
}
