<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    private function getClients()
    {
        return json_decode(Storage::get('clients.json'), true) ?? [];
    }

    private function saveClients($clients)
    {
        Storage::put('clients.json', json_encode($clients, JSON_PRETTY_PRINT));
    }

    public function index()
    {
        $clientes = $this->getClients();
        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $clientes = $this->getClients();
        
        $newCliente = [
            'id' => count($clientes) + 1,
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion,
            'medidas' => [
                'busto' => (int)$request->busto,
                'cintura' => (int)$request->cintura,
                'cadera' => (int)$request->cadera
            ],
            'historial_compras' => []
        ];

        $clientes[] = $newCliente;
        $this->saveClients($clientes);

        return redirect()->route('clientes.index')->with('success', 'Cliente registrado exitosamente');
    }

    public function edit($id)
    {
        $clientes = $this->getClients();
        $cliente = collect($clientes)->firstWhere('id', (int)$id);
        
        if (!$cliente) {
            return redirect()->route('clientes.index')->with('error', 'Cliente no encontrado');
        }

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $clientes = $this->getClients();
        
        foreach ($clientes as &$cliente) {
            if ($cliente['id'] === (int)$id) {
                $cliente['nombre'] = $request->nombre;
                $cliente['correo'] = $request->correo;
                $cliente['telefono'] = $request->telefono;
                $cliente['direccion'] = $request->direccion;
                $cliente['medidas'] = [
                    'busto' => (int)$request->busto,
                    'cintura' => (int)$request->cintura,
                    'cadera' => (int)$request->cadera
                ];
                break;
            }
        }

        $this->saveClients($clientes);
        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy($id)
    {
        $clientes = $this->getClients();
        $clientes = array_filter($clientes, function($cliente) use ($id) {
            return $cliente['id'] !== (int)$id;
        });
        
        $this->saveClients(array_values($clientes));
        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente');
    }
}
