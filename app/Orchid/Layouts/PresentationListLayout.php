<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use App\Models\Presentation;
use Orchid\Screen\Actions\Link;

class PresentationListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    public $target = 'presentations';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('title', 'Titulo')
                ->render(function (Presentation $post) {
                    return Link::make($post->name)
                        ->route('platform.presentation.edit', $post);
                }),
            TD::make('units', 'unidades'),
            TD::make('created_at', 'Creado'),
            TD::make('updated_at', 'Ultima vez editado'),
        ];
    }
}
