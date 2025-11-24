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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductResource extends Resource
{
    use HasFactory, SoftDeletes;
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag'; // Ikon Tas Belanja

    protected static ?string $navigationGroup = 'Store Management'; // Gabung dengan menu Store

    protected static ?int $navigationSort = 2; // Urutan ke-2 setelah Store

    // Logic: Seller hanya melihat produknya sendiri, Admin melihat semua
    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $user = Auth::user();

        if ($user->role === 'admin') {
            return $query;
        }

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
                                // LOGIC TOKO:
                                // Jika Admin: Bisa pilih toko mana saja.
                                // Jika Seller: Field ini Readonly (terisi otomatis).
                                Forms\Components\Select::make('store_id')
                                    ->label('Toko')
                                    ->relationship('store', 'name')
                                    ->required()
                                    // Admin boleh cari toko, Seller cuma lihat tokonya sendiri
                                    ->disabled(fn () => Auth::user()->role !== 'admin') 
                                    ->default(fn () => Auth::user()->store?->id) // Auto-fill seller store
                                    ->dehydrated(), // Tetap kirim data walau disabled

                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Produk')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state))),

                                // ... sisa form sama seperti sebelumnya ...
                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->dehydrated()
                                    ->required()
                                    ->unique(Product::class, 'slug', ignoreRecord: true),

                                Forms\Components\RichEditor::make('description')
                                    ->required()
                                    ->label('Deskripsi')
                                    ->columnSpanFull(),
                            ]),
                        
                        // ... Form Gambar ...
                        Forms\Components\Section::make('Gambar')
                            ->schema([
                                Forms\Components\FileUpload::make('image_path')
                                    ->image()
                                    ->directory('products')
                                    ->required()
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Detail & Harga')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->label('Harga')
                                    ->numeric()
                                    ->prefix('Rp')
                                    ->required(),

                                Forms\Components\TextInput::make('stock')
                                    ->label('Stok')
                                    ->numeric()
                                    ->default(1)
                                    ->required(),

                                Forms\Components\Select::make('category_id')
                                    ->label('Kategori')
                                    ->relationship('category', 'name')
                                    ->required()
                                    ->preload()
                                    ->searchable()
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
                Tables\Columns\ImageColumn::make('image_path')->label('Foto'),
                
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('category.name')
                    ->label('Kategori')
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