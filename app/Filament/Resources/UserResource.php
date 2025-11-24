<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth; // Wajib ada untuk cek permission

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users'; // Ikon User Group
    
    protected static ?string $navigationGroup = 'Admin Management'; // Masuk Grup Admin
    
    protected static ?int $navigationSort = 1;

    /**
     * SECURITY: HANYA ADMIN YANG BOLEH AKSES MENU INI
     * Seller (walaupun approved) tidak boleh melihat daftar user lain.
     */
    public static function canViewAny(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Pengguna')
                    ->description('Kelola akun Buyer, Seller, dan Admin disini.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('role')
                            ->label('Peran (Role)')
                            ->options([
                                'admin' => 'Admin',
                                'seller' => 'Seller',
                                'buyer' => 'Buyer',
                            ])
                            ->required()
                            ->live(), // Agar field di bawahnya bisa bereaksi (visible/hidden)
                            
                        Forms\Components\Select::make('seller_status')
                            ->label('Status Verifikasi Seller')
                            ->options([
                                'pending' => 'Pending (Menunggu)',
                                'approved' => 'Approved (Disetujui)',
                                'rejected' => 'Rejected (Ditolak)',
                            ])
                            // Field ini HANYA muncul jika Role yang dipilih adalah SELLER
                            ->visible(fn (Forms\Get $get) => $get('role') === 'seller'),

                        Forms\Components\TextInput::make('password')
                            ->password()
                            // Hash password sebelum simpan
                            ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
                            // Hanya simpan jika password diisi (untuk edit)
                            ->dehydrated(fn ($state) => filled($state))
                            // Wajib diisi hanya saat CREATE user baru
                            ->required(fn (string $context): bool => $context === 'create'),
                    ])->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->icon('heroicon-m-envelope'),
                
                // Badge Warna-warni untuk Role
                Tables\Columns\TextColumn::make('role')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'danger',
                        'seller' => 'warning',
                        'buyer' => 'success',
                        default => 'gray',
                    }),

                // Status Seller (Pending/Approved)
                Tables\Columns\TextColumn::make('seller_status')
                    ->label('Status Seller')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Filter berdasarkan Role
                Tables\Filters\SelectFilter::make('role')
                    ->options([
                        'seller' => 'Seller',
                        'buyer' => 'Buyer',
                        'admin' => 'Admin',
                    ]),
                // Filter User Pending (Untuk memudahkan Admin mencari Seller baru)
                Tables\Filters\SelectFilter::make('seller_status')
                    ->label('Status Verifikasi')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                // --- TOMBOL VERIFIKASI (APPROVE) ---
                Tables\Actions\Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Seller?')
                    ->modalDescription('User ini akan mendapatkan akses penuh ke Dashboard Seller.')
                    // Hanya muncul untuk Seller Pending
                    ->visible(fn (User $record) => $record->role === 'seller' && $record->seller_status === 'pending')
                    ->action(fn (User $record) => $record->update(['seller_status' => 'approved'])),

                // --- TOMBOL TOLAK (REJECT) ---
                Tables\Actions\Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Tolak Pendaftaran?')
                    ->modalDescription('User ini akan diblokir dari akses Dashboard.')
                    // Hanya muncul untuk Seller Pending
                    ->visible(fn (User $record) => $record->role === 'seller' && $record->seller_status === 'pending')
                    ->action(fn (User $record) => $record->update(['seller_status' => 'rejected'])),

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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}