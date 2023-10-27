<?php

namespace App\Orchid\Screens;

use App\Models\Presentation;
use App\Models\Product;
use App\Models\Stock;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Link;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class StockScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'inventario' => Stock::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Inventario';
    }

    public function description(): ?string
    {
        return 'Modulo de inventario';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Agregar producto al inventario')
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
            Layout::table('inventario', [
                TD::make('id')
                ->render(function (Stock $post) {
                    return Link::make($post->id)
                    ->route('platform.stock.edit', $post);
                }),
                TD::make('product_id', 'precio')
                    ->render(function (Stock $post) {
                        return Product::find($post->product_id) == null ? 'Producto eliminado' : Product::find($post->product_id)->name;
                    }),
                TD::make('presentation_id', 'presentacion')
                ->render(function (Stock $post) {
                    return Presentation::find($post->presentation_id)->name;
                }),
                TD::make('quantity', 'cantidad'),
                TD::make('limit', 'limite'),
                TD::make('created_at', 'creado'),
                TD::make('updated_at', 'creado'),
                TD::make('Actions', 'Acciones')
                ->render(function (Stock $post) {
                    return Button::make('Eliminar')
                    ->icon('trash')
                    ->confirm('Despues de eliminar, la precentacion se habrá ido para siempre')
                    ->method('delete', ['task' => $post->id]);
                }),
            ]),
            Layout::modal('presentationModal', Layout::rows([
                Select::make('productid', 'producto')
                    ->fromModel(Product::class, 'name', 'id')
                    ->title('Producto'),
                Select::make('presentationid', 'presentacion')
                    ->fromModel(Presentation::class, 'name', 'id')
                    ->title('Presentacion'),
                Input::make('stock.quantity')
                    ->title('Unidades')
                    ->placeholder('Escriba el numero de unidades'),
                Input::make('stock.limit')
                    ->title('limite')
                    ->placeholder('porcentage que debe de tener el stock'),
            ]))
                ->title('Agregar producto al inventario')
                ->applyButton('Agregar producto al inventario'),
        ];
    }


    public function create(Request $request)
    {
        // Validate form data, save task to database, etc.
        // $request->validate([
        //     'stock.name' => 'required|max:255',
        // ]);
        // dd($request->input('productid'));
        $task = new Stock();
        $task->quantity = $request->input('stock.quantity');
        $task->user_id = Auth::user()->id;
        $task->presentation_id = $request->input('presentationid'); 
        $task->limit = $request->input('stock.limit'); 
        $task->product_id = $request->input('productid'); 
        $task->save();
        Alert::info("Se agregó el producto del inventario");
    }

    public function delete(Stock $task)
    {
        $task->delete();
        Alert::info("Se eliminó el producto del inventario");
    }
}
