<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Product;
use App\Orchid\Layouts\ProductListLayout;
use Orchid\Screen\Actions\Link;

class ProductListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'products' => Product::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'ProductListScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nuevo producto')
            ->icon('pencil')
            ->route('platform.product.edit')
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
            ProductListLayout::class
        ];
    }
}
