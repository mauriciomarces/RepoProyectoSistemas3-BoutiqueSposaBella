<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cliente;

class FleteController extends Controller
{
    public function index()
    {
        // Obtener fletes con información del cliente
        $fletes = DB::table('fletes')
            ->leftJoin('cliente', 'fletes.cliente_id', '=', 'cliente.ID_cliente')
            ->select(
                'fletes.*',
                'cliente.nombre as cliente_nombre',
                'cliente.correo as cliente_correo'
            )
            ->orderBy('fletes.created_at', 'desc')
            ->get();

        return view('fletes.index', compact('fletes'));
    }

    public function create()
    {
        return view('fletes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:cliente,ID_cliente',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|regex:/^[67]\d{7}$/',
            'descripcion' => 'nullable|string|max:1000'
        ]);

        try {
            DB::table('fletes')->insert([
                'cliente_id' => $request->cliente_id,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono ?? null,
                'descripcion' => $request->descripcion ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return redirect()->route('fletes.index')->with('success', 'Flete registrado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al registrar el flete. Por favor, intente nuevamente.');
        }
    }

    public function show($id)
    {
        try {
            $flete = DB::table('fletes')
                ->leftJoin('cliente', 'fletes.cliente_id', '=', 'cliente.ID_cliente')
                ->select(
                    'fletes.*',
                    'cliente.nombre as cliente_nombre',
                    'cliente.correo as cliente_correo',
                    'cliente.telefono as cliente_telefono',
                    'cliente.direccion as cliente_direccion'
                )
                ->where('fletes.id', $id)
                ->first();

            if (!$flete) {
                return response()->json(['error' => 'Flete no encontrado'], 404);
            }

            return response()->json($flete);
        } catch (\Exception $e) {
            Log::error('Error en show flete: ' . $e->getMessage());
            return response()->json(['error' => 'Error al cargar el flete: ' . $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        $flete = DB::table('fletes')->where('id', $id)->first();
        if (!$flete) {
            return redirect()->route('fletes.index')->with('error', 'Flete no encontrado.');
        }
        return view('fletes.edit', compact('flete'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'required|exists:cliente,ID_cliente',
            'direccion' => 'required|string|max:255',
            'telefono' => 'nullable|string|regex:/^[67]\d{7}$/',
            'descripcion' => 'nullable|string|max:1000'
        ]);

        try {
            DB::table('fletes')->where('id', $id)->update([
                'cliente_id' => $request->cliente_id,
                'direccion' => $request->direccion,
                'telefono' => $request->telefono ?? null,
                'descripcion' => $request->descripcion ?? null,
                'updated_at' => now()
            ]);

            return redirect()->route('fletes.index')->with('success', 'Flete actualizado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error al actualizar el flete. Por favor, intente nuevamente.');
        }
    }

    public function destroy($id)
    {
        DB::table('fletes')->where('id', $id)->delete();
        return redirect()->route('fletes.index')->with('success', 'Flete eliminado correctamente.');
    }

    // API endpoint para búsqueda de clientes en tiempo real
    public function searchClientes(Request $request)
    {
        try {
            $query = $request->get('q', '');

            if (empty($query)) {
                return response()->json([]);
            }

            // Buscar clientes activos (no eliminados) por palabras
            $palabras = explode(' ', trim($query));
            $clientesQuery = Cliente::query()->whereNull('deleted_at'); // Solo clientes activos

            foreach ($palabras as $palabra) {
                if (!empty($palabra)) {
                    $clientesQuery->where('nombre', 'like', '%' . $palabra . '%');
                }
            }

            $clientes = $clientesQuery->limit(10)->get(['ID_cliente', 'nombre', 'correo', 'telefono', 'direccion']);

            return response()->json($clientes);
        } catch (\Exception $e) {
            \Log::error('Error en searchClientes: ' . $e->getMessage());
            return response()->json(['error' => 'Error al buscar clientes'], 500);
        }
    }
}
