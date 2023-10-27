<?php

namespace App\Orchid\Screens;


use Orchid\Screen\Screen;
use App\Models\Presentation;
use App\Orchid\Layouts\PresentationListLayout;
use Orchid\Screen\Actions\Link;

class PresentationListScreen extends Screen
{

  
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'presentations' => Presentation::paginate()
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'PresentationListScreen';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Link::make('Nueva presentacion')
            ->icon('pencil')
            ->route('platform.presentation.edit')
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
            PresentationListLayout::class
        ];
    }
}
