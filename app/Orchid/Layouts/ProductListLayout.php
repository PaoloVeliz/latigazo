<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Product;
use Orchid\Screen\Actions\Link;

class ProductListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'products';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('name', 'Nombre')
                ->render(function (Product $post) {
                    return Link::make($post->name)
                        ->route('platform.product.edit', $post);
                }),
            TD::make('price', 'precio por unidad'),
            TD::make('created_at', 'Creado'),
            TD::make('updated_at', 'Ultima vez editado'),
        ];
    }
}
