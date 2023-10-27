<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Product;
use App\Models\Presentation;
use App\Models\Stock;
use App\Orchid\Layouts\StockListLayout;
use Orchid\Screen\Actions\Link;
class StockListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'stocks' => Stock::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Todos los productos en el inventario';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nuevo producto en el inventario')
            ->icon('pencil')
            ->route('platform.stock.edit')
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            StockListLayout::class
        ];
    }
}
