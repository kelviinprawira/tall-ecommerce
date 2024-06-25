<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Product Information')->schema([
                        Forms\Components\TextInput::make('name')
                            ->live(true)
                            ->afterStateUpdated(fn(Set $set, ?string $state) => $set('slug', Str::slug
                            ($state)))
                            ->required(),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->disabled()
                            ->dehydrated()
                            ->unique(Product::class, 'slug', ignoreRecord: true),
                        Forms\Components\MarkdownEditor::make('description')->columnSpanFull()
                            ->fileAttachmentsDirectory('products'),
                    ])->columns(2),
                    Forms\Components\Section::make('images')->schema([
                        Forms\Components\FileUpload::make('images')->multiple()
                            ->directory('products')
                            ->maxFiles(5),
                    ]),
                ])->columnSpan(2),
                Forms\Components\Group::make()->schema([
                    Forms\Components\Section::make('Product Pricing')->schema([
                        Forms\Components\TextInput::make('price')->numeric()->prefix('IDR')
                    ]),
                    Forms\Components\Section::make('Assosiations')->schema([
                        Forms\Components\Select::make('category_id')->required()
                            ->preload()
                            ->searchable()
                            ->relationship('category', 'name'),
                        Forms\Components\Select::make('brand_id')->required()
                            ->preload()
                            ->searchable()
                            ->relationship('brand', 'name'),
                    ]),
                    Forms\Components\Section::make('status')->schema([
                        Forms\Components\Toggle::make('in_stock')->required()
                            ->default(true),
                        Forms\Components\Toggle::make('is_active')->required()
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')->required(),
                        Forms\Components\Toggle::make('on_sale')->required(),
                    ])
                ])->columnSpan(1),

            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('images'),
                Tables\Columns\TextColumn::make('price')
                    ->money('idr', true,)
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('in_stock')
                    ->boolean(),
                Tables\Columns\IconColumn::make('on_sale')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')->relationship('category', 'name'),
                Tables\Filters\SelectFilter::make('brand')->relationship('brand', 'name'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
