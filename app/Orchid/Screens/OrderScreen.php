<?php

namespace App\Orchid\Screens;


use App\Models\Presentation;
use App\Models\Order;
use App\Models\Stock;
use App\Models\Product;
use App\Models\User;
use Illuminate\Console\View\Components\Task;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Orchid\Screen\TD;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Link;

use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

class OrderScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'Ordenes' => Order::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Ordenes';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Crear una orden')
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
             Layout::table('Ordenes', [
                TD::make('id'),
                TD::make('product_id', 'Producto')
                    ->render(function (Order $post) {
                        return Product::find($post->product_id) == null ? 'Eliminado del inventario' :  Product::find($post->product_id)->name;
                    }),
                TD::make('presentation_id', 'presentacion')
                    ->render(function (Order $post) {
                        return Presentation::find($post->presentation_id) == null ? 'Fue eliminado de la base de datos' : Presentation::find($post->presentation_id)->name;
                    }),
                TD::make('status', 'Estado'),
                TD::make('quantity', 'cantidad'),
                TD::make('subtotal', 'total'),
                TD::make('created_at', 'creado'),
                TD::make('updated_at', 'creado'),
                TD::make('Actions', 'Accion')
                ->render(function (Order $post) {
                    return Button::make('Eliminar')
                    ->icon('trash')
                    ->confirm('Despues de eliminar, la precentacion se habrá ido para siempre')
                    ->method('delete', ['task' => $post->id]);
                }),
                TD::make('Verify', 'Accion')
                ->render(function (Order $post) {
                    return Button::make('Aceptar')
                    ->icon('check')
                    ->confirm('Despues de aceptar, se descontara de tu inventario')
                    ->method('accept', ['task' => $post->id]);
                }),
            ]),
            Layout::modal('presentationModal', Layout::rows([
                Select::make('product_id', 'producto')
                    ->fromModel(Product::class, 'name')
                    ->title('Producto a pedir'),
                Select::make('presentation_id', 'presentacion')
                    ->fromModel(Presentation::class, 'name')
                    ->title('Presentacion a pedir'),
                Select::make('to', 'Destinatario')
                    ->fromModel(User::class, 'name')
                    ->title('Destinatario'),
                Input::make('order.quantity')
                    ->title('Cantidad a pedir'),
                Input::make('order.subtotal')
                    ->title('Sub total'),
                Select::make('status')
                ->options([
                        'En Proceso'   => 'En Proceso',
                        'Terminada' => 'Terminada',
                    ])
                    ->title('Estado'),
                
            ]))
                ->title('Crear orden')
                ->applyButton('Crear orden'),
        ];
    }

    public function create(Request $request)
    {
        $task = new Order();
        $task->quantity = $request->input('order.quantity');
        $task->from = Auth::user()->id;
        $task->presentation_id = $request->input('presentation_id'); 
        // $task->limit = $request->input('order.limit'); 
        $task->product_id = $request->input('product_id'); 
        $task->subtotal = $request->input('order.subtotal'); 
        $task->status = $request->input('status'); 
        $task->to = $request->input('to');
        $task->from_type = "";
        $task->to_type = "";
        $task->save();
        Alert::info("Se agregó el producto del inventario");
    }

    public function delete(Order $task)
    {
        $task->delete();
        Alert::info("Se eliminó la orden");
    }

    public function accept(Order $task)
    {
        $stock = Stock::where('product_id', $task->product_id)->first();
        if($stock == null) {
            Alert::error('Debe de ingresar primero el produco en el inventario');   
        } else {
            $stock->quantity = $stock->quantity - $task->quantity;
            $stock->update(['quantity',$stock->quantity ]);
            $update_task = Order::find($task->id);
            $task->status = "Terminada";
            $task->save();
            Alert::info("Se aceptó la orden");
            return redirect()->route('platform.order');
        }


    }
}
