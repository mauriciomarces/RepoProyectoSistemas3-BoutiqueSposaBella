<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;

class ClienteController extends Controller
{
    // Mostrar clientes con filtros
    public function index(Request $request)
    {
        $query = Cliente::query();

        // Filtros
        if ($request->filled('nombre')) {
            $query->where('nombre', 'like', '%' . $request->nombre . '%');
        }
        if ($request->filled('busto')) {
            $query->where('busto', $request->busto);
        }
        if ($request->filled('cintura')) {
            $query->where('cintura', $request->cintura);
        }
        if ($request->filled('cadera')) {
            $query->where('cadera', $request->cadera);
        }

        $clientes = $query->get()->map(function($cliente) {
            return (object)[
                'id' => $cliente->ID_cliente,
                'nombre' => $cliente->nombre,
                'apellido' => '',
                'correo' => $cliente->correo,
                'email' => $cliente->correo,
                'telefono' => $cliente->telefono,
                'direccion' => $cliente->direccion,
                'busto' => $cliente->busto ?? 0,
                'cintura' => $cliente->cintura ?? 0,
                'cadera' => $cliente->cadera ?? 0,
                'medidas' => (object)[
                    'busto' => $cliente->busto ?? 0,
                    'cintura' => $cliente->cintura ?? 0,
                    'cadera' => $cliente->cadera ?? 0
                ],
                'historial_compras' => []
            ];
        });

        if ($request->ajax()) {
            return view('clientes.partials.clients_table', compact('clientes'))->render();
        }

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'busto' => 'required|numeric',
            'cintura' => 'required|numeric',
            'cadera' => 'required|numeric',
        ]);

        Cliente::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion ?? '',
            'busto' => $request->busto,
            'cintura' => $request->cintura,
            'cadera' => $request->cadera,
            'password' => bcrypt('123456')
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente');
    }

    public function show($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        // Obtener historial de compras (pedidos y transacciones)
        $historialCompras = DB::table('pedido')
            ->leftJoin('transaccion', 'pedido.ID_cliente', '=', 'transaccion.ID_cliente')
            ->leftJoin('detalle_pedido', 'pedido.ID_pedido', '=', 'detalle_pedido.ID_pedido')
            ->leftJoin('producto', 'detalle_pedido.ID_producto', '=', 'producto.ID_producto')
            ->where('pedido.ID_cliente', $id)
            ->select(
                'pedido.ID_pedido',
                'pedido.fecha_pedido',
                'pedido.estado as estado_pedido',
                'pedido.fecha_entrega',
                'pedido.tipo_pedido',
                'transaccion.monto',
                'transaccion.tipo_transaccion',
                'transaccion.estado as estado_pago',
                'producto.nombre as producto_nombre',
                'detalle_pedido.cantidad',
                'detalle_pedido.precio_unitario'
            )
            ->orderBy('pedido.fecha_pedido', 'desc')
            ->get()
            ->groupBy('ID_pedido')
            ->map(function($items) {
                $firstItem = $items->first();
                return (object)[
                    'id' => $firstItem->ID_pedido,
                    'fecha' => $firstItem->fecha_pedido,
                    'tipo' => $firstItem->tipo_pedido,
                    'estado' => $firstItem->estado_pedido,
                    'fecha_entrega' => $firstItem->fecha_entrega,
                    'monto_total' => $firstItem->monto ?? 0,
                    'estado_pago' => $firstItem->estado_pago ?? 'pendiente',
                    'productos' => $items->map(function($item) {
                        return (object)[
                            'nombre' => $item->producto_nombre,
                            'cantidad' => $item->cantidad,
                            'precio_unitario' => $item->precio_unitario
                        ];
                    })->toArray()
                ];
            })->values();

        $clienteObj = (object)[
            'id' => $cliente->ID_cliente,
            'nombre' => $cliente->nombre,
            'apellido' => '',
            'correo' => $cliente->correo,
            'email' => $cliente->correo,
            'telefono' => $cliente->telefono,
            'direccion' => $cliente->direccion,
            'busto' => $cliente->busto ?? 0,
            'cintura' => $cliente->cintura ?? 0,
            'cadera' => $cliente->cadera ?? 0,
            'medidas' => (object)[
                'busto' => $cliente->busto ?? 0,
                'cintura' => $cliente->cintura ?? 0,
                'cadera' => $cliente->cadera ?? 0
            ],
            'historial_compras' => $historialCompras
        ];

        return view('clientes.partials.detalle_cliente_modal', ['cliente' => $clienteObj])->render();
    }

    public function edit($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return redirect()->route('clientes.index')->with('error', 'Cliente no encontrado');
        }

        // FIX: Ahora pasamos el objeto Cliente directamente con todas sus propiedades
        $clienteObj = (object)[
            'ID_cliente' => $cliente->ID_cliente,  // ← ESTO ES LO QUE FALTABA
            'id' => $cliente->ID_cliente,
            'nombre' => $cliente->nombre,
            'apellido' => '',
            'correo' => $cliente->correo,
            'telefono' => $cliente->telefono,
            'direccion' => $cliente->direccion ?? '',
            'busto' => $cliente->busto ?? 0,
            'cintura' => $cliente->cintura ?? 0,
            'cadera' => $cliente->cadera ?? 0,
            'medidas' => (object)[
                'busto' => $cliente->busto ?? 0,
                'cintura' => $cliente->cintura ?? 0,
                'cadera' => $cliente->cadera ?? 0
            ]
        ];

        return view('clientes.edit', ['cliente' => $clienteObj]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'busto' => 'required|numeric',
            'cintura' => 'required|numeric',
            'cadera' => 'required|numeric',
        ]);

        $cliente = Cliente::find($id);
        if (!$cliente) {
            return redirect()->route('clientes.index')->with('error', 'Cliente no encontrado');
        }

        $cliente->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion ?? '',
            'busto' => $request->busto,
            'cintura' => $request->cintura,
            'cadera' => $request->cadera,
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy($id)
    {
        $cliente = Cliente::find($id);
        if ($cliente) {
            $cliente->delete();
        }
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente');
    }
}