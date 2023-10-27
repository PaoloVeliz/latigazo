<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Order;
use Orchid\Screen\Actions\Link;

class OrderListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'orders';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
         return [
            TD::make('name', 'Nombre')
                ->render(function (Order $post) {
                    return Link::make("Acciones")
                        ->route('platform.order.edit', $post);
                }),
            TD::make('product_id', 'producto'),
            TD::make('presentation_id', 'presentacion'),
            TD::make('quantity', 'cantidad'),
            TD::make('subtotal', 'sub total'),
            TD::make('status', 'estado de orden'),
            TD::make('from', 'autor'),
            TD::make('to', 'mandado a'),
            TD::make('created_at', 'Creado'),
            TD::make('updated_at', 'Ultima vez editado'),
        ];
    }
}
