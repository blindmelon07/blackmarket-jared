<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SellerApplicationResource\Pages;
use App\Filament\Admin\Resources\SellerApplicationResource\RelationManagers;
use App\Models\SellerApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SellerApplicationResource extends Resource
{
    protected static ?string $model = SellerApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
    ->label('Applicant')
    ->relationship('user', 'name')
    ->searchable()
    ->preload()
    ->disabled(fn($record) => $record !== null),
                Forms\Components\TextInput::make('business_name')->disabled(),
                Forms\Components\TextInput::make('phone')->disabled(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                Forms\Components\Textarea::make('admin_note')->label('Admin Note'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Applicant'),
                Tables\Columns\TextColumn::make('business_name'),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\BadgeColumn::make('status')->colors([
                    'primary' => 'pending',
                    'success' => 'approved',
                    'danger' => 'rejected',
                ]),
                Tables\Columns\TextColumn::make('user.roles.name')
    ->label('Roles')
    ->badge()
    ->formatStateUsing(fn ($state) => collect($state)->join(', ')),
                
                Tables\Columns\TextColumn::make('created_at')->dateTime(),
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
            'index' => Pages\ListSellerApplications::route('/'),
            'create' => Pages\CreateSellerApplication::route('/create'),
            'edit' => Pages\EditSellerApplication::route('/{record}/edit'),
        ];
    }
}
