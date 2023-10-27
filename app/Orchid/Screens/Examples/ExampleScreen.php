<?php

namespace App\Orchid\Screens\Examples;

use App\Models\Order;
use App\Models\Product;
use App\Models\Presentation;
use App\Orchid\Layouts\Examples\ChartBarExample;
use App\Orchid\Layouts\Examples\ChartLineExample;
use App\Orchid\Layouts\OrderListLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\ModalToggle;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Repository;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ExampleScreen extends Screen
{
    /**
     * Fish text for the table.
     */
    public const TEXT_EXAMPLE = 'Lorem ipsum at sed ad fusce faucibus primis, potenti inceptos ad taciti nisi tristique
    urna etiam, primis ut lacus habitasse malesuada ut. Lectus aptent malesuada mattis ut etiam fusce nec sed viverra,
    semper mattis viverra malesuada quam metus vulputate torquent magna, lobortis nec nostra nibh sollicitudin
    erat in luctus.';

    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'charts'  => [
                [
                    'name'   => 'Producto 1',
                    'values' => [25, 40, 30, 35, 8, 52, 17],
                    'labels' => ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6', 'Semana 7'],
                ],
                [
                    'name'   => 'Producto 2',
                    'values' => [25, 50, -10, 15, 18, 32, 27],
                    'labels' => ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6', 'Semana 7'],
                ],
                [
                    'name'   => 'Producto 3',
                    'values' => [15, 20, -3, -15, 58, 12, -17],
                    'labels' => ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6', 'Semana 7'],
                ],
                [
                    'name'   => 'Producto 4',
                    'values' => [10, 33, -8, -3, 70, 20, -34],
                    'labels' => ['Semana 1', 'Semana 2', 'Semana 3', 'Semana 4', 'Semana 5', 'Semana 6', 'Semana 7'],
                ],
            ],
            'table'   => [
                new Repository(['id' => 100, 'name' => self::TEXT_EXAMPLE, 'price' => 10.24, 'created_at' => '01.01.2020']),
                new Repository(['id' => 200, 'name' => self::TEXT_EXAMPLE, 'price' => 65.9, 'created_at' => '01.01.2020']),
                new Repository(['id' => 300, 'name' => self::TEXT_EXAMPLE, 'price' => 754.2, 'created_at' => '01.01.2020']),
                new Repository(['id' => 400, 'name' => self::TEXT_EXAMPLE, 'price' => 0.1, 'created_at' => '01.01.2020']),
                new Repository(['id' => 500, 'name' => self::TEXT_EXAMPLE, 'price' => 0.15, 'created_at' => '01.01.2020']),

            ],
            'metrics' => [
                'sales'    => ['value' => number_format(6851), 'diff' => 10.08],
                'visitors' => ['value' => number_format(24668), 'diff' => -30.76],
                'orders'   => ['value' => number_format(10000), 'diff' => 0],
                'total'    => number_format(65661),
            ],
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
        return 'Buenos dias ' . Auth::user()->name;
    }

    /**
     * Display header description.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return 'Vista rapida de la situacion actual';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [

            // Button::make('Show toast')
            //     ->method('showToast')
            //     ->novalidate()
            //     ->icon('bag'),

            // ModalToggle::make('Launch demo modal')
            //     ->modal('exampleModal')
            //     ->method('showToast')
            //     ->icon('full-screen'),

            Button::make('Descargar data')
                ->method('export')
                ->icon('cloud-download')
                ->rawClick()
                ->novalidate(),

            // DropDown::make('Dropdown button')
            //     ->icon('folder-alt')
            //     ->list([

            //         Button::make('Action')
            //             ->method('showToast')
            //             ->icon('bag'),

            //         Button::make('Another action')
            //             ->method('showToast')
            //             ->icon('bubbles'),

            //         Button::make('Something else here')
            //             ->method('showToast')
            //             ->icon('bulb'),

            //         Button::make('Confirm button')
            //             ->method('showToast')
            //             ->confirm('If you click you will see a toast message')
            //             ->novalidate()
            //             ->icon('shield'),
                // ]),

        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return string[]|\Orchid\Screen\Layout[]
     */
    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Ventas del dia'    => 'metrics.sales',
                'Gastos del dia' => 'metrics.visitors',
                'Ordenes pendientes' => 'metrics.orders',
                'Ingresos totales' => 'metrics.total',
            ]),

            Layout::columns([
                ChartLineExample::make('charts', 'Flujo de productos')
                    ->description('Flujo de productos del dia de hoy.'),

                ChartBarExample::make('charts', 'Inventario por productos por semana')
                    ->description('Cantidad en el inventario por productos'),
            ]),

            Layout::table('Ordenes', [
                    TD::make('id'),
                    TD::make('product_id', 'precio')
                        ->render(function (Order $post) {
                            return Product::find($post->product_id)->name;
                        }),
                    TD::make('presentation_id', 'presentacion')
                        ->render(function (Order $post) {
                            return Presentation::find($post->presentation_id)->name;
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
        ])];
    }

    /**
     * @param Request $request
     */
    public function showToast(Request $request): void
    {
        Toast::warning($request->get('toast', 'Hello, world! This is a toast message.'));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export()
    {
        return response()->streamDownload(function () {
            $csv = tap(fopen('php://output', 'wb'), function ($csv) {
                fputcsv($csv, ['header:col1', 'header:col2', 'header:col3']);
            });

            collect([
                ['row1:col1', 'row1:col2', 'row1:col3'],
                ['row2:col1', 'row2:col2', 'row2:col3'],
                ['row3:col1', 'row3:col2', 'row3:col3'],
            ])->each(function (array $row) use ($csv) {
                fputcsv($csv, $row);
            });

            return tap($csv, function ($csv) {
                fclose($csv);
            });
        }, 'File-name.csv');
    }
}
