<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;

use App\Models\Stock;
use App\Models\Presentation;
use App\Models\Product;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
// use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Alert;

class StockEditScreen extends Screen
{

    public $stock;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Stock $stock): array
    {
        return [
            'stock' => $stock
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->stock->exists ? 'Agregando nuevo producto al inventario' : 'Editando producto en el inventario';
    }

    /**
    * The description is displayed on the user's screen under the heading
    */
    public function description(): ?string
    {
        return "Inventario";
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make('Crear presentacion')
                ->icon('pencil')
                ->method('createOrUpdate')
                ->canSee(!$this->stock->exists),
            Button::make('Editar')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->stock->exists),
            Button::make('Eliminar')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->stock->exists),
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
            Layout::rows([
                Input::make('stock.id')
                    ->title('Id'),
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
            ])
        ];
    }

     /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrUpdate(Request $request)
    {
        $requested_presentation = new Stock();
        // $task = new Stock();
        $requested_presentation->quantity = $request->input('stock.quantity');
        $requested_presentation->presentation_id = $request->input('presentationid'); 
        $requested_presentation->limit = $request->input('stock.limit'); 
        $requested_presentation->product_id = $request->input('productid');

        $target_presentation = Stock::find($request->get('stock')["id"]);
        if ($target_presentation == null ) {
            $requested_presentation->save();
            Alert::success("No se ha encontrado el producto en el inventario, por ende se ha creado una nueva");
        } else {
            $target_presentation->update(['quantity' => $requested_presentation->quantity, 'presentation_id' => $requested_presentation->presentation_id, 'limit' => $requested_presentation->limit, 'product_id'=>$requested_presentation->product_id]);
            Alert::success('Se ha editado exitosamente la presentacion');
        }
        return redirect()->route('platform.stock.list');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        $requested_presentation = new Stock();
        $requested_presentation->fill($request->get('stock'));
        $target_presentation = Stock::find($request->get('stock')["id"]);
        if ($target_presentation == null ) {
            Alert::error("Se produjo un error al momento de eliminar");
        } else {
            $target_presentation->delete();
            Alert::success('Se ha borrado la presentacion de forma exitosa');
        }
        return redirect()->route('platform.presentation.list');
    }
}
