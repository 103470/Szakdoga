<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Brands\BrandModel;
use App\Models\Brands\Type;
use App\Models\Brands\Vintage;
use App\Models\FuelType;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BrandModelController extends Controller
{
    public function index()
    {
        $models = BrandModel::with(['type', 'fuelType'])
            ->orderBy('name')
            ->paginate(20);

        return view('admin.markak.modellek.index', compact('models'));
    }

    public function create()
    {
        $types = Type::orderBy('name')->get();
        $fuelTypes = FuelType::orderBy('name')->get();
        $frames = Vintage::pluck('frame')->filter()->unique()->sort()->values();

        $column = DB::select("SHOW COLUMNS FROM `brand_models` WHERE Field = 'body_type'");
        $enumColumn = $column[0]->Type;
        preg_match("/^enum\('(.*)'\)$/", $enumColumn, $matches);
        $bodyTypes = explode("','", $matches[1]);

        return view('admin.markak.modellek.create', compact('types', 'fuelTypes', 'frames', 'bodyTypes'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateModel($request);

        BrandModel::create($validated);

        return redirect()->route('admin.markak.modellek.index')
            ->with('success', 'Modell sikeresen létrehozva!');
    }

    public function edit(BrandModel $model)
    {
        $types = Type::orderBy('name')->get();
        $fuelTypes = FuelType::orderBy('name')->get();
        $frames = Vintage::pluck('frame')->filter()->unique()->sort()->values();

        $column = DB::select("SHOW COLUMNS FROM `brand_models` WHERE Field = 'body_type'");
        $enumColumn = $column[0]->Type;
        preg_match("/^enum\('(.*)'\)$/", $enumColumn, $matches);
        $bodyTypes = explode("','", $matches[1]);

        return view('admin.markak.modellek.edit', compact('model', 'types', 'fuelTypes', 'frames', 'bodyTypes'));
    }

    public function update(Request $request, BrandModel $model)
    {
        $validated = $this->validateModel($request);

        $model->update($validated);

        return redirect()->route('admin.markak.modellek.index')
            ->with('success', 'Modell sikeresen frissítve!');
    }

    public function destroy(BrandModel $model)
    {
        if ($model->partVehicles) {
            foreach ($model->partVehicles as $pv) {
                $pv->forceDelete();
            }
        }

        $model->forceDelete();

        return redirect()->route('admin.markak.modellek.index')
            ->with('success', 'Modell és minden hozzá tartozó rekord törölve!');
    }

    private function validateModel(Request $request)
    {
        $today = Carbon::today();
        $minDate = Carbon::create(1886, 1, 1);

        return $request->validate([
            'type_id' => 'required|exists:brand_types,id',
            'name' => 'required|string|max:255',
            'ccm' => 'nullable|integer|min:50|max:20000',
            'kw_hp' => 'nullable|string|max:50',
            'engine_type' => 'nullable|string|max:50',
            'fuel_type_id' => 'nullable|exists:fuel_types,id',
            'frame' => 'nullable|string|max:255',
            'body_type' => 'nullable|string|max:255',

            'year_from' => ['required','integer','min:' . $minDate->year],
            'month_from' => ['required','integer','min:1','max:12', function ($attr, $value, $fail) use ($request, $minDate) {
                $from = Carbon::create($request->year_from, $value, 1);
                if ($from->lt($minDate)) {
                    $fail('A kezdő dátum nem lehet korábbi, mint 1886/01.');
                }
            }],
            'year_to' => ['nullable','integer', function($attr,$value,$fail) use($today) {
                if ($value && $value > $today->year) {
                    $fail('A záró év nem lehet nagyobb a mai évnél.');
                }
            }],
            'month_to' => ['nullable','integer','min:1','max:12', function($attr,$value,$fail) use($request,$today) {
                if (!$request->year_to) return;
                $to = Carbon::create($request->year_to,$value,1);
                if ($to->gt($today)) {
                    $fail('A záró dátum nem lehet későbbi, mint a mai nap.');
                }
            }],
        ]);
    }
}
