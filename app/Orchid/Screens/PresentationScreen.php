<?php

namespace App\Orchid\Screens;

use App\Models\Presentation;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Fields\Input;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\ModalToggle;
use Illuminate\Http\Request;
use Orchid\Screen\TD;
use Orchid\Screen\Actions\Link;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;

class PresentationScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'presentaciones' => Presentation::latest()->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Presentaciones';
    }


    public function description(): ?string
    {
        return 'Modulo de presentaciones';
    }
    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            ModalToggle::make('Crear presentacion')
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
            Layout::table('presentaciones', [
                TD::make('name', 'titulo')
                ->render(function (Presentation $post) {
                    return Link::make($post->name)
                    ->route('platform.presentation.edit', $post);
                }),
                TD::make('id'),
                TD::make('units', 'unidades'),
                TD::make('created_at', 'creado'),
                TD::make('Actions', 'Acciones')
                ->render(function (Presentation $post) {
                    return Button::make('Eliminar')
                    ->icon('trash')
                    ->confirm('Despues de eliminar, la precentacion se habrá ido para siempre')
                    ->method('delete', ['task' => $post->id]);
                }),
            ]),
            Layout::modal('presentationModal', Layout::rows([
                Input::make('presentation.name')
                    ->title('Name')
                    ->placeholder('Escriba el nombre de la presentacion')
                    ->help('El nombre de la presentacion ejemplo: caja o docena.'),
                Input::make('presentation.units')
                    ->title('Unidades')
                    ->placeholder('Escriba el numero de unidades')
                    ->help('Las unidades que va a tener esta presentacion.'),
            ]))
                ->title('Crear presentacion')
                ->applyButton('Agregar presentacion'),
        ];
    }



    //Actions

    public function create(Request $request)
    {
        // Validate form data, save task to database, etc.
        $request->validate([
            'presentation.name' => 'required|max:255',
        ]);

        $task = new Presentation();
        $task->name = $request->input('presentation.name');
        $task->units = $request->input('presentation.units');
        $task->save();
        Alert::success('Se ha creado exitosamente la presentacion');
    }

    public function edit(Presentation $task) {
        // $request->validate([
        //     'presentation.name' => 'required|max:255',
        // ]);

        
        // $task = Presentation::find($request);
        // $task->name = $request->input('presentation.name');
        // $task->units = $request->input('presentation.units');
        $task->save();    
    }

    public function delete(Presentation $task)
    {
        $task->delete();
        Alert::info('Se ha eliminado exitosamente la presentacion');

    }
}
