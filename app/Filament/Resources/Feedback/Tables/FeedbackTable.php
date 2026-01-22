<?php

namespace App\Filament\Resources\Feedback\Tables;

use App\Enums\FeedbackStatus;
use App\Models\Feedback;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class FeedbackTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ip_address')
                    ->label('IP Guest')
                    ->searchable()
                    ->sortable()
                    ->fontFamily('mono')
                    ->copyable()
                    ->icon('heroicon-o-globe-alt')
                    ->description(fn(Feedback $record) => Str::limit($record->user_agent, 40)), // Menampilkan device di bawah IP

                TextColumn::make('message')
                    ->label('Feedback Message')
                    ->limit(50)
                    ->wrap()
                    ->searchable()
                    ->icon('heroicon-o-chat-bubble-bottom-center-text'),

                TextColumn::make('status')
                    ->badge()
                    ->color(fn(FeedbackStatus $state) => match ($state) {
                        FeedbackStatus::NEW => 'danger',
                        FeedbackStatus::REVIEWED => 'gray',
                    })
                    ->icon(fn(FeedbackStatus $state) => match ($state) {
                        FeedbackStatus::NEW => 'heroicon-o-sparkles',
                        FeedbackStatus::REVIEWED => 'heroicon-o-check-circle',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(FeedbackStatus::class)
                    ->label('Status'),
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('gray'),

                    Action::make('mark_as_reviewed')
                        ->label('Mark as Closed')
                        ->icon('heroicon-o-check')
                        ->color('success')
                        ->action(function (Feedback $record) {
                            $record->update(['status' => FeedbackStatus::REVIEWED]);
                        })
                        ->visible(fn(Feedback $record) => $record->status === FeedbackStatus::NEW),
                ])
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
