<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;

class ProductResource extends Resource
{
   protected static ?string $model = Product::class;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResults(string $search): Collection
    {
        return static::getModel()::query()
            ->where('name', 'like', "%{$search}%")
            ->orWhere('description', 'like', "%{$search}%")
            ->get()
            ->map(function ($record) {

                return[
                  'title' => $record->name,
                    'url' => self::getUrl('view',['record' => $record]),
                ];

            });
    }

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';






    private static array $statuses = [
        'in stock' => 'in stock',
        'sold out' => 'sold out',
        'coming soon' => 'coming soon',
    ];



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('main data')
                    ->description('anythings you want to fill in')
                    ->label(__('main data'))

            ->schema([

                Forms\Components\TextInput::make('name')
                    ->label(__('Product name'))
                    ->required()
                    ->unique(ignoreRecord: true),
             //       ->live(onBlur: true)
            //        ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', str())),
           //     Forms\Components\TextInput::make('slug')
            //        ->required(),

  /*              Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name') // Assuming 'name' is the field you want to display
                    ->required(),*/


                Forms\Components\TextInput::make('price')
                    ->required()
                    ->rule('numeric'),

                //A forms that you can choose Radio/select more you want
                Forms\Components\Radio::make('status')
                    ->options(self::$statuses)
                    ->required(),


                Forms\Components\Select::make('tag_id')
                    ->label('Select a Tag')
                    ->relationship('tag', 'name')
                    ->multiple(),




/*
                Forms\Components\Wizard\Step::make('Additional data')
                    ->schema([]),
public static function form(Form $form): Form
{
    return $form
        ->schema([
            Wizard::make([
                Step::make('Product Details')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Product Name'),
                        TextInput::make('price')
                            ->required()
                            ->label('Price')
                            ->numeric(),
                    ]),
                Step::make('Additional data')
                    ->schema([
                        // Additional fields go here
                    ]),
            ])
        ]);
} */


              /*  ->options([
                       'in stock' => 'in stock',
                      'sold out' =>  'sold out',
                      'coming soon' =>  'coming soon',
                 ]) */


                Forms\Components\Select::make('category_id')  // Reference the foreign key field
                ->label('Category')
                    ->relationship('category', 'name')  // Pulls the category name from the Category model
                    ->required(),


                //example that you put ->columnSpan(2)  < mean make full width which form section you want.
                Forms\Components\RichEditor::make('description')
                  //  ->columnSpan(2)
                    ->columnSpanFull()
                  //  ->required(),

                //
            ])
            ])
            //this columns make every section all in one straight line
        ->columns(1);
        //this columns make every section all in one row
        // ->columns(4);

    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns(components: array(

            //    Tables\Columns\TextColumn::make('name')
            //        ->sortable()
            //        ->searchable(isIndividual: false, isGlobal: true),
                Tables\Columns\TextInputColumn::make('name')
                  ->rules(['required', 'min:3']),

                Tables\Columns\TextColumn::make('price')
                    ->sortable()
                    ->money('usd')
                    ->getStateUsing(function (Product $record):float {
                        return $record->price / 100;
                    }),


                //Make the status feature
               //  Tables\Columns\TextColumn::make('status'), OR use bottom

                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'in stock' => 'in stock',
                        'sold out' => 'sold out',
                        'coming soon' => 'coming soon',
                    ]),

                //make the category select feature
                Tables\Columns\TextColumn::make('category.name')
                ->label('Category')
                ->sortable()
                ->searchable(),
                //

                Tables\Columns\TextColumn::make('created_at')
                ->dateTime(),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->onColor('success') // default primary color
                    ->offColor('danger'), // default value gray

                Tables\Columns\BadgeColumn::make('tag.name')


            ))


            ->filters([
                Tables\Filters\Filter::make('is_featured')
                ->query(fn(Builder $query): Builder => $query->where('is_featured',true),),
                //
                Tables\Filters\SelectFilter::make('status')
                    ->options(self::$statuses),

                Tables\Filters\SelectFilter::make('category')
                    ->relationship('category', 'name')




            /*    ->options([
                       'in stock' => 'in stock',
                       'sold out' => 'sold out',
                       'coming soon' => 'coming soon',
                   ])   */
                //
            ])


            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),

            ])
            ->defaultSort('price', 'desc')
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),

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
            'view' => Pages\ViewProduct::route('/{record}')
        ];
    }
}
