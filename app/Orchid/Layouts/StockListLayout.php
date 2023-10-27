<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Stock;
use App\Models\Product;
use App\Models\Presentation;
use Orchid\Screen\Actions\Link;

class StockListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'stocks';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'Id'),
            TD::make('name', 'Acciones')
                ->render(function (Stock $post) {
                    return Link::make("Acciones")
                        ->route('platform.stock.edit', $post);
                }),
            TD::make('product_id', 'precio')
            ->render(function (Stock $post) {
                return Product::find($post->product_id)->name;
            }),
            TD::make('presentation_id', 'presentacion')
            ->render(function (Stock $post) {
                return Presentation::find($post->presentation_id)->name;
            }),
            TD::make('quantity', 'cantidad'),
            TD::make('limit', 'limite en stock'),
            TD::make('created_at', 'Creado'),
            TD::make('updated_at', 'Ultima vez editado'),
        ];
    }
}
