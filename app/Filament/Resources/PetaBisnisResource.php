<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PetaBisnisResource\Pages;
use App\Filament\Resources\PetaBisnisResource\RelationManagers;
use App\Models\PetaBisnis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PetaBisnisResource extends Resource
{
    protected static ?string $model = PetaBisnis::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('pj')
                    ->maxLength(50),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(50),
                Forms\Components\TextInput::make('nohp')
                    ->maxLength(17),
                Forms\Components\TextInput::make('kta')
                    ->maxLength(19),
                Forms\Components\TextInput::make('tempat_lahir')
                    ->maxLength(20),
                Forms\Components\TextInput::make('tgl_lahir')
                    ->maxLength(13),
                Forms\Components\TextInput::make('alamat')
                    ->maxLength(158),
                Forms\Components\TextInput::make('nama_perusahaan')
                    ->maxLength(55),
                Forms\Components\TextInput::make('tahun_berdiri')
                    ->maxLength(44),
                Forms\Components\TextInput::make('badan_usaha')
                    ->maxLength(18),
                Forms\Components\Textarea::make('sektor')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('deskripsi')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('tenaga_kerja')
                    ->maxLength(19),
                Forms\Components\TextInput::make('modal_usaha')
                    ->maxLength(29),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pj')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nohp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tempat_lahir')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun_berdiri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('badan_usaha')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tenaga_kerja')
                    ->searchable(),
                Tables\Columns\TextColumn::make('modal_usaha')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPetaBisnis::route('/'),
            'create' => Pages\CreatePetaBisnis::route('/create'),
            'edit' => Pages\EditPetaBisnis::route('/{record}/edit'),
        ];
    }
}
