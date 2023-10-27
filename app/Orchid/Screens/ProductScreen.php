<?php

namespace App\Orchid\Screens;
use App\Models\Product;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use Illuminate\Http\Request;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

class ProductScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'productos' => Product::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Productos';
    }

    public function description(): ?string
    {
        return 'Modulo de productos';
    }
    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Crear producto')
            ->modal('presentationModal')
            ->method('create')
            ->icon('plus'),
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
            Layout::table('productos', [
                TD::make('name', 'titulo')
                ->render(function (Product $post) {
                    return Link::make($post->name)
                    ->route('platform.product.edit', $post);
                }),
                TD::make('id'),
                TD::make('price', 'precio'),
                TD::make('created_at', 'creado'),
                TD::make('Actions', 'Acciones')
                ->render(function (Product $post) {
                    return Button::make('Eliminar')
                    ->icon('trash')
                    ->confirm('Despues de eliminar, la precentacion se habrá ido para siempre')
                    ->method('delete', ['task' => $post->id]);
                }),
            ]),
            Layout::modal('presentationModal', Layout::rows([
                Input::make('product.name')
                    ->title('Name')
                    ->placeholder('Escriba el nombre de la presentacion')
                    ->help('El nombre de la presentacion ejemplo: caja o docena.'),
                Input::make('product.price')
                    ->title('Unidades')
                    ->placeholder('Escriba el numero de unidades')
                    ->help('Las unidades que va a tener esta presentacion.'),
            ]))
                ->title('Crear presentacion')
                ->applyButton('Agregar presentacion'),
        ];
    }

    public function create(Request $request)
    {
        // Validate form data, save task to database, etc.
        $request->validate([
            'product.name' => 'required|max:255',
        ]);

        $task = new Product();
        $task->name = $request->input('product.name');
        $task->price = $request->input('product.price');
        $task->save();
    }


    public function delete(Product $task)
    {
        $task->delete();
    }
}
