<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make("title")
                    ->required()
                    ->maxLength(255),
                TextInput::make("price")
                    ->required()
                    ->numeric()
                    ->prefix("$")
                    ->step(0.01),
                Textarea::make("description")
                    ->columnSpanFull()
                    ->rows(4),
                TextInput::make("category")
                    ->maxLength(255),
                DateTimePicker::make("crawled_at")
                    ->displayFormat("Y-m-d H:i:s"),
                Repeater::make("images")
                    ->relationship()
                    ->schema([
                        TextInput::make("image_url")
                            ->label("Image URL")
                            ->url()
                            ->required(),
                        TextInput::make("alt_text")
                            ->label("Alt Text")
                            ->maxLength(255),
                        TextInput::make("sort_order")
                            ->label("Sort Order")
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2)
                    ->addActionLabel("Add Image")
                    ->collapsible(),
            ]);
    }
}
