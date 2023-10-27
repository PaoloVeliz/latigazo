<?php

namespace App\Orchid\Screens;

use App\Models\Presentation;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Button;
// use App\Models\User;
use Illuminate\Http\Request;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Upload;
use Orchid\Support\Facades\Alert;

class PresentationEditScreen extends Screen
{

    public $presentation;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Presentation $presentation): array
    {
        return [
            'presentation' => $presentation
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return $this->presentation->exists ? 'Creando una nueva presentacion' : 'Editando presentacion';
    }
    
     /**
     * The description is displayed on the user's screen under the heading
     */
    public function description(): ?string
    {
        return "Presentaciones";
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
                ->canSee(!$this->presentation->exists),

            Button::make('Editar')
                ->icon('note')
                ->method('createOrUpdate')
                ->canSee($this->presentation->exists),

            Button::make('Eliminar')
                ->icon('trash')
                ->method('remove')
                ->canSee($this->presentation->exists),
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
                Input::make('presentation.id')
                    ->enabled()
                    ->title('id'),
                Input::make('presentation.name')
                    ->title('Nombre'),

                Input::make('presentation.units')
                    ->title('Unidades')
                    ->rows(3),
                // Relation::make('post.author')
                //     ->title('Author')
                //     ->fromModel(User::class, 'name'),

                // Quill::make('post.body')
                //     ->title('Main text'),

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
        
        $requested_presentation = new Presentation();
        $requested_presentation->fill($request->get('presentation'));
        $target_presentation = Presentation::find($request->get('presentation')["id"]);
        if ($target_presentation == null ) {
            $requested_presentation->save();
            Alert::success("No se ha encontrado la presentacion, por ende se ha creado una nueva");
        } else {
            $target_presentation->update(['name' => $requested_presentation->name, 'units' => $requested_presentation->units]);
            Alert::success('Se ha editado exitosamente la presentacion');
        }
        return redirect()->route('platform.presentation.list');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        $requested_presentation = new Presentation();
        $requested_presentation->fill($request->get('presentation'));
        $target_presentation = Presentation::find($request->get('presentation')["id"]);
        if ($target_presentation == null ) {
            Alert::error("Se produjo un error al momento de eliminar");
        } else {
            $target_presentation->delete();
            Alert::success('Se ha borrado la presentacion de forma exitosa');
        }
        return redirect()->route('platform.presentation.list');
    }
}
