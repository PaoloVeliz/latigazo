<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\Order;
use App\Orchid\Layouts\OrderListLayout;
use Orchid\Screen\Actions\Link;

class OrderListScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'orders' => Order::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'OrderListScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nueva orden')
            ->icon('pencil')
            ->route('platform.order.edit')
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
            OrderListLayout::class
        ];
    }
}
