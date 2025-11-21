<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RareBrands\Vintage;
use App\Models\RareBrands\Type;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class RareBrandVintageController extends Controller
{
    public function index()
    {
        $vintages = Vintage::with('type')->orderBy('name')->paginate(20);
        return view('admin.ritkamarkak.evjaratok.index', compact('vintages'));
    }

    public function create()
    {
        $types = Type::orderBy('name')->get();

        $column = DB::select("SHOW COLUMNS FROM `rarebrand_vintages`");
        foreach ($column as $col) {
            if ($col->Field === 'body_type') {
                $enumColumn = $col->Type;
                preg_match("/^enum\('(.*)'\)$/", $enumColumn, $matches);
                $bodyTypes = explode("','", $matches[1]);
            }
        }

        return view('admin.ritkamarkak.evjaratok.create', compact('types', 'bodyTypes'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateVintage($request);
        Vintage::create($validated);

        return redirect()->route('admin.ritkamarkak.evjaratok.index')
            ->with('success', 'Évjárat sikeresen létrehozva!');
    }

    public function edit(Vintage $vintage)
    {
        $types = Type::orderBy('name')->get();

        $column = DB::select("SHOW COLUMNS FROM `rarebrand_vintages`");
        foreach ($column as $col) {
            if ($col->Field === 'body_type') {
                $enumColumn = $col->Type;
                preg_match("/^enum\('(.*)'\)$/", $enumColumn, $matches);
                $bodyTypes = explode("','", $matches[1]);
            }
        }

        return view('admin.ritkamarkak.evjaratok.edit', compact('vintage', 'types', 'bodyTypes'));
    }

    public function update(Request $request, Vintage $vintage)
    {
        $validated = $this->validateVintage($request);
        $vintage->update($validated);

        return redirect()->route('admin.ritkamarkak.evjaratok.index')
            ->with('success', 'Évjárat sikeresen frissítve!');
    }

    public function destroy(Vintage $vintage)
    {
        if (method_exists($vintage, 'models') && $vintage->models) {
            foreach ($vintage->models as $model) {
                $model->forceDelete();
            }
        }

        $vintage->forceDelete();

        return redirect()->route('admin.ritkamarkak.evjaratok.index')
            ->with('success', 'Évjárat és minden hozzá tartozó rekord törölve!');
    }

    private function validateVintage(Request $request)
    {
        $today = Carbon::today();
        $minDate = Carbon::create(1886, 1, 1);

        $column = DB::select("SHOW COLUMNS FROM `rarebrand_vintages` WHERE Field = 'body_type'");
        $enumColumn = $column[0]->Type;
        preg_match("/^enum\('(.*)'\)$/", $enumColumn, $matches);
        $bodyTypes = explode("','", $matches[1]);

        return $request->validate([
            'type_id' => 'required|exists:rarebrand_types,id',
            'name' => 'required|string|max:255',
            'frame' => 'nullable|string|max:255',
            'body_type' => 'required|string|in:' . implode(',', $bodyTypes),
            'year_from' => [
                'required',
                'integer',
                'min:' . $minDate->year,
            ],
            'month_from' => [
                'required',
                'integer',
                'min:1',
                'max:12',
                function ($attr, $value, $fail) use ($request, $minDate) {
                    $from = Carbon::create($request->year_from, $value, 1);
                    if ($from->lt($minDate)) {
                        $fail('A kezdő dátum nem lehet korábbi, mint 1886/01.');
                    }
                }
            ],
            'year_to' => [
                'nullable',
                'integer',
                function ($attr, $value, $fail) {
                    if ($value && $value > now()->year) {
                        $fail('A záró év nem lehet nagyobb a mai évnél.');
                    }
                }
            ],
            'month_to' => [
                'nullable',
                'integer',
                'min:1',
                'max:12',
                function ($attr, $value, $fail) use ($request, $today) {
                    if (!$request->year_to) return;
                    $to = Carbon::create($request->year_to, $value, 1);
                    if ($to->gt($today)) {
                        $fail('A záró dátum nem lehet későbbi, mint a mai nap.');
                    }
                }
            ],
        ]);
    }
}
