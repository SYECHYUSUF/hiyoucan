<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'E-Commerce Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make('Informasi Dasar Pesanan')
                            ->schema([
                                // Menampilkan informasi Buyer (read-only)
                                Forms\Components\TextInput::make('user.name')
                                    ->label('Buyer Name')
                                    ->disabled(),
                                
                                Forms\Components\TextInput::make('created_at')
                                    ->label('Tanggal Order')
                                    ->disabled(),

                                // Field Status (hanya Admin yang boleh edit di form detail)
                                Forms\Components\Select::make('status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'processing' => 'Processing',
                                        'shipped' => 'Shipped',
                                        'completed' => 'Completed',
                                        'cancelled' => 'Cancelled',
                                    ])
                                    ->visible(auth()->user()->role === 'admin') 
                                    ->required(),
                                
                                Forms\Components\TextInput::make('total_price')
                                    ->money('IDR')
                                    ->label('Total Harga')
                                    ->disabled(),
                            ])->columns(2),
                        
                        Forms\Components\Section::make('Alamat Pengiriman')
                            ->schema([
                                Forms\Components\Textarea::make('shipping_address')
                                    ->rows(3)
                                    ->disabled()
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpan(['lg' => 2]),

                Forms\Components\Group::make()
                    ->schema([
                        // Sidebar: Tidak ada field yang harus diisi, hanya untuk tampilan
                    ])
                    ->columnSpan(['lg' => 1]),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('Order ID')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Buyer')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('total_price')
                    ->money('IDR')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'processing' => 'primary',
                        'shipped' => 'info',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Tanggal Order')
                    ->sortable(),
            ])
            ->filters([
                // Filter status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->label('Filter Status'),
            ])
            ->actions([
                // Aksi Update Status (khusus Seller/Admin)
                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->icon('heroicon-o-truck')
                    ->color('primary')
                    ->visible(fn () => auth()->user()->role !== 'buyer')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'processing' => 'Processing',
                                'shipped' => 'Shipped',
                                'completed' => 'Completed',
                                'cancelled' => 'Cancelled',
                            ])
                            ->required()
                            ->default(fn ($record) => $record->status),
                    ])
                    ->action(function (array $data, $record) {
                        $record->update(['status' => $data['status']]);
                        \Filament\Notifications\Notification::make()
                            ->title('Status pesanan diperbarui.')
                            ->success()
                            ->send();
                    }),
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
            // Relation Manager untuk menampilkan Item Pesanan (Order Items)
            RelationManagers\ItemsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        
        // Jika user adalah ADMIN, tampilkan semua order
        if (auth()->user()->role === 'admin') {
            return $query;
        }

        // Jika user adalah SELLER, filter Order berdasarkan produk yang dijual oleh Seller ini
        if (auth()->user()->role === 'seller') {
            return $query->whereHas('items.product.user', function (Builder $q) {
                // q merujuk ke Model User (Seller) yang memiliki produk di OrderItem
                $q->where('id', auth()->id()); 
            });
        }
        
        // Default (misalnya, untuk Buyer yang tidak sengaja mengakses): Batasi berdasarkan ID
        return $query->where('user_id', auth()->id()); 
    }
}