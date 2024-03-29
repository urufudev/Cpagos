<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Historial;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HistorialController extends Controller
{
    public function index()
    {
        if (auth()->user()->hasRole('admin')) {
            $historiales = Historial::orderBy('created_at', 'ASC')->get();

        } else {
            $historiales = Historial::where('estado', 'creado')->orderBy('created_at', 'ASC')->get();
        }

        return response()->json(['historiales' => $historiales]);
    }

    public function update(Request $request, $id)
    {
        $historial = Historial::findOrfail($id);

        $request->validate([
            'certificado' => ['required', 'numeric', Rule::unique('historiales')->ignore($historial->id)],
        ]);

        $historial->certificado = $request->certificado;
        if ($historial->save()) {
            return response()->json(['updated' => true], 200);
        }
    }

    public function destroy($id)
    {
        $historial = Historial::findOrFail($id);
        $historial->estado = 'eliminado';
        if ($historial->save()) {
            return response()->json(['deleted' => true], 200);
        }
    }
}
