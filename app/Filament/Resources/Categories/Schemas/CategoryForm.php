<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Set $set, $state) {
                        $slug = Str::slug($state);
                        $existingSlug = Category::where('slug', $slug)->first();
                        if ($existingSlug) {
                            $slug = $slug . '-' . uniqid();
                        }
                        $set('slug', $slug);
                    }),

                TextInput::make('slug')
                    ->hint('Generated from the title automatically.')
                    ->required()->readOnly()->disabled()->dehydrated(true),
            ]);
    }
}
