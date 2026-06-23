<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LayananResource\Pages;
use App\Filament\Resources\LayananResource\RelationManagers;
use App\Models\Layanan;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\ActionGroup;
use Illuminate\Support\Str;

class LayananResource extends Resource
{
    protected static ?string $model = Layanan::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Layanan';

    protected static ?string $modelLabel = 'Layanan';

    protected static ?string $pluralModelLabel = 'Layanan';

    protected static ?string $navigationGroup = 'Manajemen Konten';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Layanan')
                    ->description('Masukkan detail informasi layanan yang akan ditampilkan')
                    ->icon('heroicon-o-information-circle')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('nama')
                                    ->label('Nama Layanan')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: Web Development')
                                    ->helperText('Nama layanan yang akan ditampilkan kepada klien')
                                    ->reactive()
                                    ->afterStateUpdated(function ($set, $state) {
                                        $slug = Str::slug($state);
                                        $set('slug', $slug);
                                    })

                                    ->columnSpan(1),

                                TextInput::make('slug')
                                    ->label('Slug Layanan')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('Contoh: web-development')
                                    ->helperText('Slug layanan yang akan digunakan di URL')
                                    ->columnSpan(1),

                                TextInput::make('urutan')
                                    ->label('Urutan Tampil')
                                    ->numeric()
                                    ->default(0)
                                    ->minValue(0)
                                    ->placeholder('0')
                                    ->helperText('Urutan tampil layanan (semakin kecil semakin atas)')
                                    ->columnSpan(1),
                            ]),
                    ]),

                RichEditor::make('deskripsi')
                    ->label('Deskripsi Layanan')
                    ->placeholder('Masukkan deskripsi detail tentang layanan ini...')
                    ->helperText('Deskripsi lengkap tentang layanan yang ditawarkan')
                    ->toolbarButtons([
                        'attachFiles',
                        'blockquote',
                        'bold',
                        'bulletList',
                        'codeBlock',
                        'h2',
                        'h3',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpanFull(),

                Section::make('Visual & Tampilan')
                    ->description('Atur tampilan visual untuk layanan')
                    ->icon('heroicon-o-photo')
                    ->collapsible()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                FileUpload::make('gambar_utama')
                                    ->label('Gambar')
                                    ->image()
                                    ->directory('icon-layanan')
                                    ->imageEditor()
                                    ->imageEditorAspectRatios([
                                        '1:1',
                                        '16:9',
                                        '4:3',
                                    ])
                                    ->maxSize(2048)
                                    ->acceptedFileTypes(['image/png', 'image/jpg', 'image/jpeg', 'image/svg+xml'])
                                    ->helperText('Upload Gambar Utama untuk layanan (PNG, JPG, SVG - Max 2MB)')
                                    ->columnSpan(1),


                            ]),
                    ]),

                Section::make('Pengaturan')
                    ->description('Pengaturan tambahan untuk layanan')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsible()
                    ->collapsed()
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Toggle::make('is_active')
                                    ->label('Status Aktif')
                                    ->helperText('Aktifkan untuk menampilkan layanan di website')
                                    ->default(true)
                                    ->columnSpan(1),

                                Toggle::make('is_featured')
                                    ->label('Layanan Unggulan')
                                    ->helperText('Tandai sebagai layanan unggulan')
                                    ->default(false)
                                    ->columnSpan(1),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('gambar_utama')
                    ->label('Gambar')
                    ->circular()
                    ->size(50)
                    ->defaultImageUrl(url('/images/placeholder-icon.png')),

                TextColumn::make('slug')
                    ->label('Nama Layanan')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::SemiBold)
                    ->description(fn(Layanan $record): string => strip_tags($record->deskripsi ?? '') ?
                        Str::limit(strip_tags($record->deskripsi), 50) : 'Tidak ada deskripsi'),




                TextColumn::make('urutan')
                    ->label('Urutan')
                    ->sortable()
                    ->badge()
                    ->color('gray'),

                ToggleColumn::make('is_active')
                    ->label('Aktif')
                    ->sortable(),

                ToggleColumn::make('is_featured')
                    ->label('Unggulan')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status Aktif')
                    ->placeholder('Semua layanan')
                    ->trueLabel('Hanya yang aktif')
                    ->falseLabel('Hanya yang tidak aktif'),

                TernaryFilter::make('is_featured')
                    ->label('Layanan Unggulan')
                    ->placeholder('Semua layanan')
                    ->trueLabel('Hanya unggulan')
                    ->falseLabel('Bukan unggulan'),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->color('info'),
                    Tables\Actions\EditAction::make()
                        ->color('warning'),
                    Tables\Actions\DeleteAction::make()
                        ->color('danger'),
                ])
                    ->label('Aksi')
                    ->icon('heroicon-m-ellipsis-vertical')
                    ->size('sm')
                    ->color('gray')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Aktifkan Terpilih')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_active' => true]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),

                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Nonaktifkan Terpilih')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function ($records) {
                            $records->each(function ($record) {
                                $record->update(['is_active' => false]);
                            });
                        })
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('urutan', 'asc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
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
            'index' => Pages\ListLayanans::route('/'),
            'create' => Pages\CreateLayanan::route('/create'),
            'view' => Pages\ViewLayanan::route('/{record}'),
            'edit' => Pages\EditLayanan::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }
}
