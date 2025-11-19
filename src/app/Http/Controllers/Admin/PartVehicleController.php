<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PartVehicle;
use App\Models\OemNumber;
use App\Models\Brands\BrandModel;
use App\Models\RareBrands\RareBrandModel;

class PartVehicleController extends Controller
{
    public function index()
    {
        $items = PartVehicle::with(['oemNumber', 'brandModel', 'rareBrandModel'])
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return view('admin.termekkapcsolas.index', compact('items'));
    }

    public function create()
    {
        $oemNumbers = OemNumber::orderBy('oem_number')->get();

        $brandModels = BrandModel::orderBy('name')->get();
        $rareBrandModels = RareBrandModel::orderBy('name')->get();

        $brandCodes = $brandModels->map(function($model) {
            return [
                'code' => $model->unique_code,
                'label' => $model->unique_code . ' (' 
                    . $model->name . ', '
                    . $model->engine_type . ', '
                    . $model->ccm . ' ccm, '
                    . $model->kw_hp . ' LE, '
                    . $model->year_from . '/' . $model->month_from . ' - '
                    . $model->year_to . '/' . $model->month_to . ')',
            ];
        });

        $rareBrandCodes = $rareBrandModels->map(function($model) {
            return [
                'code' => $model->unique_code,
                'label' => $model->unique_code . ' (' 
                    . $model->name . ', '
                    . $model->engine_type . ', '
                    . $model->ccm . ' ccm, '
                    . $model->kw_hp . ' LE, '
                    . $model->year_from . '/' . $model->month_from . ' - '
                    . $model->year_to . '/' . $model->month_to . ')',
            ];
        });


        return view('admin.termekkapcsolas.create', compact(
            'oemNumbers',
            'brandModels',
            'rareBrandModels',
            'brandCodes',
            'rareBrandCodes'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'oem_number_id' => 'required|exists:oem_numbers,id',
            'model_source'  => 'required|in:brand,rarebrand',
            'unique_code'   => 'required',
        ]);

        PartVehicle::create([
            'oem_number_id' => $request->oem_number_id,
            'model_source'  => $request->model_source,
            'unique_code'   => $request->unique_code,
        ]);

        return redirect()->route('admin.termekkapcsolas.index')
            ->with('success', 'Jármű sikeresen hozzáadva.');
    }

    public function edit($id)
    {
        $item = PartVehicle::findOrFail($id);

        $oemNumbers = OemNumber::orderBy('oem_number')->get();
        $brandModels = BrandModel::orderBy('name')->get();
        $rareBrandModels = RareBrandModel::orderBy('name')->get();

        $brandCodes = $brandModels->map(function($model) {
            return [
                'code' => $model->unique_code,
                'label' => $model->unique_code . ' (' 
                    . $model->name . ', '
                    . $model->engine_type . ', '
                    . $model->ccm . ' ccm, '
                    . $model->kw_hp . ' LE, '
                    . $model->year_from . '/' . $model->month_from . ' - '
                    . $model->year_to . '/' . $model->month_to . ')',
            ];
        });

        $rareBrandCodes = $rareBrandModels->map(function($model) {
            return [
                'code' => $model->unique_code,
                'label' => $model->unique_code . ' (' 
                    . $model->name . ', '
                    . $model->engine_type . ', '
                    . $model->ccm . ' ccm, '
                    . $model->kw_hp . ' LE, '
                    . $model->year_from . '/' . $model->month_from . ' - '
                    . $model->year_to . '/' . $model->month_to . ')',
            ];
        });

        return view('admin.termekkapcsolas.edit', compact(
            'item',
            'oemNumbers',
            'brandModels',
            'rareBrandModels',
            'brandCodes',
            'rareBrandCodes'
        ));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'oem_number_id' => 'required|exists:oem_numbers,id',
            'model_source'  => 'required|in:brand,rarebrand',
            'unique_code'   => 'required',
        ]);

        $item = PartVehicle::findOrFail($id);

        $item->update([
            'oem_number_id' => $request->oem_number_id,
            'model_source'  => $request->model_source,
            'unique_code'   => $request->unique_code,
        ]);

        return redirect()->route('admin.termekkapcsolas.index')
            ->with('success', 'Jármű sikeresen frissítve.');
    }

    public function destroy($id)
    {
        $partVehicle = PartVehicle::findOrFail($id);
        $partVehicle->forceDelete();

        return redirect()->route('admin.termekkapcsolas.index')
            ->with('success', 'Jármű törölve.');
    }
}
