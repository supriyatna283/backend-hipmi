<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DatabasePerusahaanResource\Pages;
use App\Models\DatabasePerusahaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DatabasePerusahaanResource extends Resource
{
    protected static ?string $model = DatabasePerusahaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $navigationLabel = 'Database Perusahaan';

    protected static ?string $modelLabel = 'Perusahaan';

    protected static ?string $pluralModelLabel = 'Database Perusahaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informasi Perusahaan')
                    ->schema([
                        Forms\Components\TextInput::make('nama_perusahaan')
                            ->label('Nama Perusahaan')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('bidang_usaha')
                            ->label('Bidang Usaha')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nib')
                            ->label('NIB (Nomor Induk Berusaha)')
                            ->maxLength(50),

                        Forms\Components\Textarea::make('alamat_kantor')
                            ->label('Alamat Kantor')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('deskripsi')
                            ->label('Deskripsi Perusahaan')
                            ->placeholder('Ceritakan tentang perusahaan Anda...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Data Owner / Pemilik')
                    ->schema([
                        Forms\Components\TextInput::make('nama_owner')
                            ->label('Nama Owner (Pemilik)')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('nik')
                            ->label('NIK / KTP')
                            ->required()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('nohp_owner')
                            ->label('No. HP Owner')
                            ->tel()
                            ->required()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('nohp_perusahaan')
                            ->label('No. HP PT / CV')
                            ->tel()
                            ->maxLength(20),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Dokumen & Media')
                    ->schema([
                        Forms\Components\FileUpload::make('logo_perusahaan')
                            ->label('Logo Perusahaan (PT / CV)')
                            ->image()
                            ->disk('public')
                            ->directory('perusahaan/logo')
                            ->imageEditor()
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                            ->maxSize(5120),

                        Forms\Components\FileUpload::make('company_profile')
                            ->label('Company Profile')
                            ->disk('public')
                            ->directory('perusahaan/company_profile')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240),

                        Forms\Components\FileUpload::make('berkas_badan_hukum')
                            ->label('Berkas Badan Hukum')
                            ->disk('public')
                            ->directory('perusahaan/berkas_badan_hukum')
                            ->acceptedFileTypes(['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                            ->maxSize(10240),

                        Forms\Components\FileUpload::make('foto_produk')
                            ->label('Foto Produk / Galeri')
                            ->multiple()
                            ->disk('public')
                            ->directory('perusahaan/foto_produk')
                            ->image()
                            ->imageEditor()
                            ->reorderable()
                            ->maxFiles(20)
                            ->maxSize(20480)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('logo_perusahaan')
                    ->label('Logo')
                    ->disk('public')
                    ->circular(),

                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->searchable()
                    ->sortable()
                    ->formatStateUsing(fn($state) => mb_strtoupper($state)),

                Tables\Columns\TextColumn::make('bidang_usaha')
                    ->label('Bidang Usaha')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_owner')
                    ->label('Owner')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nohp_owner')
                    ->label('No. HP Owner'),

                Tables\Columns\TextColumn::make('nib')
                    ->label('NIB')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Didaftarkan')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('nama_perusahaan', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDatabasePerusahaan::route('/'),
            'create' => Pages\CreateDatabasePerusahaan::route('/create'),
            'edit'   => Pages\EditDatabasePerusahaan::route('/{record}/edit'),
        ];
    }
}
