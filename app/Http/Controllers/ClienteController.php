<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClienteController extends Controller
{
    protected $clientsJsonPath;

    public function __construct()
    {
        $this->clientsJsonPath = storage_path('app/clients.json');
    }

    private function getClients()
    {
        if (!file_exists($this->clientsJsonPath)) {
            file_put_contents($this->clientsJsonPath, json_encode([]));
        }
        
        // Obtener como array para procesar
        $clientsArray = json_decode(file_get_contents($this->clientsJsonPath), true) ?? [];
        
        // Normalizar cada cliente para asegurar que tenga todas las propiedades
        $normalizedClients = array_map(function($cliente) {
            // Asegurar que historial_compras sea un array válido
            $historialCompras = [];
            if (isset($cliente['historial_compras'])) {
                if (is_array($cliente['historial_compras'])) {
                    $historialCompras = $cliente['historial_compras'];
                } elseif (is_object($cliente['historial_compras'])) {
                    $historialCompras = (array)$cliente['historial_compras'];
                }
            }

            return (object)[
                'id' => $cliente['id'] ?? 0,
                'nombre' => $cliente['nombre'] ?? '',
                'apellido' => $cliente['apellido'] ?? '',
                'correo' => $cliente['correo'] ?? $cliente['email'] ?? '',
                'email' => $cliente['email'] ?? $cliente['correo'] ?? '',
                'telefono' => $cliente['telefono'] ?? '',
                'direccion' => $cliente['direccion'] ?? '',
                'busto' => $cliente['busto'] ?? $cliente['medidas']['busto'] ?? 0,
                'cintura' => $cliente['cintura'] ?? $cliente['medidas']['cintura'] ?? 0,
                'cadera' => $cliente['cadera'] ?? $cliente['medidas']['cadera'] ?? 0,
                'medidas' => (object)[
                    'busto' => $cliente['medidas']['busto'] ?? $cliente['busto'] ?? 0,
                    'cintura' => $cliente['medidas']['cintura'] ?? $cliente['cintura'] ?? 0,
                    'cadera' => $cliente['medidas']['cadera'] ?? $cliente['cadera'] ?? 0
                ],
                'historial_compras' => $historialCompras
            ];
        }, $clientsArray);
        
        return $normalizedClients;
    }

    private function saveClients($clients)
    {
        file_put_contents($this->clientsJsonPath, json_encode($clients, JSON_PRETTY_PRINT));
    }

    // Mostrar clientes con soporte AJAX para filtros
    public function index(Request $request)
    {
        $clientes = $this->getClients();

        // Filtros
        if ($request->filled('nombre')) {
            $clientes = array_filter($clientes, fn($c) => 
                stripos($c->nombre, $request->nombre) !== false || 
                stripos($c->apellido, $request->nombre) !== false
            );
        }
        if ($request->filled('busto')) {
            $clientes = array_filter($clientes, fn($c) => $c->busto == (int)$request->busto);
        }
        if ($request->filled('cintura')) {
            $clientes = array_filter($clientes, fn($c) => $c->cintura == (int)$request->cintura);
        }
        if ($request->filled('cadera')) {
            $clientes = array_filter($clientes, fn($c) => $c->cadera == (int)$request->cadera);
        }

        // Retornar vista parcial si es AJAX
        if ($request->ajax()) {
            return view('clientes.partials.clients_table', compact('clientes'))->render();
        }

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function show($id)
    {
        $clientes = $this->getClients();
        $cliente = collect($clientes)->firstWhere('id', (int)$id);

        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        // Asegurar que historial_compras sea un array
        if (is_object($cliente->historial_compras)) {
            $cliente->historial_compras = (array)$cliente->historial_compras;
        }

        return view('clientes.partials.detalle_cliente_modal', compact('cliente'))->render();
    }

    public function store(Request $request)
    {
        // Validar datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'busto' => 'required|numeric',
            'cintura' => 'required|numeric',
            'cadera' => 'required|numeric',
        ]);

        // Obtener clientes como array para manipulación
        $clientesArray = json_decode(file_get_contents($this->clientsJsonPath), true) ?? [];
        
        $newCliente = [
            'id' => count($clientesArray) > 0 ? max(array_column($clientesArray, 'id')) + 1 : 1,
            'nombre' => $request->nombre,
            'apellido' => $request->apellido ?? '',
            'correo' => $request->correo,
            'email' => $request->correo,
            'telefono' => $request->telefono,
            'direccion' => $request->direccion ?? '',
            'busto' => (int)$request->busto,
            'cintura' => (int)$request->cintura,
            'cadera' => (int)$request->cadera,
            'medidas' => [
                'busto' => (int)$request->busto,
                'cintura' => (int)$request->cintura,
                'cadera' => (int)$request->cadera
            ],
            'historial_compras' => []
        ];
        
        $clientesArray[] = $newCliente;
        file_put_contents($this->clientsJsonPath, json_encode($clientesArray, JSON_PRETTY_PRINT));

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
        // Validar datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'telefono' => 'required|string|max:20',
            'busto' => 'required|numeric',
            'cintura' => 'required|numeric',
            'cadera' => 'required|numeric',
        ]);

        // Usar array para manipulación
        $clientesArray = json_decode(file_get_contents($this->clientsJsonPath), true) ?? [];
        
        $found = false;
        foreach ($clientesArray as &$cliente) {
            if ($cliente['id'] === (int)$id) {
                $cliente['nombre'] = $request->nombre;
                $cliente['apellido'] = $request->apellido ?? $cliente['apellido'] ?? '';
                $cliente['correo'] = $request->correo;
                $cliente['email'] = $request->correo;
                $cliente['telefono'] = $request->telefono;
                $cliente['direccion'] = $request->direccion ?? $cliente['direccion'] ?? '';
                $cliente['busto'] = (int)$request->busto;
                $cliente['cintura'] = (int)$request->cintura;
                $cliente['cadera'] = (int)$request->cadera;
                $cliente['medidas'] = [
                    'busto' => (int)$request->busto,
                    'cintura' => (int)$request->cintura,
                    'cadera' => (int)$request->cadera
                ];
                $found = true;
                break;
            }
        }
        
        if (!$found) {
            return redirect()->route('clientes.index')->with('error', 'Cliente no encontrado');
        }
        
        file_put_contents($this->clientsJsonPath, json_encode($clientesArray, JSON_PRETTY_PRINT));

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente');
    }

    public function destroy($id)
    {
        $clientesArray = json_decode(file_get_contents($this->clientsJsonPath), true) ?? [];
        $clientesArray = array_filter($clientesArray, fn($c) => $c['id'] !== (int)$id);
        file_put_contents($this->clientsJsonPath, json_encode(array_values($clientesArray), JSON_PRETTY_PRINT));

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente');
    }
}