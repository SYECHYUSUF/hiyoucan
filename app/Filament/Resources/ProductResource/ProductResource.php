<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker'; // Ikon ala skincare/kimia

    protected static ?string $navigationGroup = 'Store Management';

    // Query untuk membatasi Seller hanya melihat produk mereka sendiri
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user && $user->role === 'admin') {
            return $query;
        }

        // Asumsi: Produk terhubung ke Seller via store_id -> Store -> User
        // Atau jika Product langsung punya user_id (tergantung struktur database terakhir)
        // Di migrasi 'products' yang kita buat, ada 'store_id' DAN 'user_id' (opsional). 
        // Mari kita gunakan user_id dulu agar aman sesuai migrasi terakhir.
        
        return $query->where('user_id', $user->id); 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Produk')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required(),

                                Forms\Components\RichEditor::make('description')
                                    ->label('Deskripsi')
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Section::make('Gambar')
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->label('Foto Produk')
                                    ->image()
                                    ->directory('products')
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail & Harga')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga (IDR)')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\TextInput::make('stock')
                                    ->label('Stok')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),

                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),
                                        Forms\Components\TextInput::make('slug')->required(),
                                    ]),
                            ]),
                    ])->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Foto'),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->description(fn (Product $record) => Str::limit(strip_tags($record->description), 50)),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable()
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('price')
                    ->money('IDR', locale: 'id')
                    ->sortable(),

                Tables\Columns\TextColumn::make('stock')
                    ->numeric()
                    ->sortable()
                    ->color(fn (string $state): string => $state <= 5 ? 'danger' : 'success'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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