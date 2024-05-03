<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeriesFormRequest;
use App\Models\Serie;
use App\Repositories\SeriesRepository;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    public function __construct(private SeriesRepository $repository)
    {
    }
    public function index(Request $request)
    {
        $query = Serie::query();
        if ($request->has('nome')){
            $query->where('nome', $request->nome);
        }

        return $query->paginate(5);
        
    }

    public function store(SeriesFormRequest $request)
    {
        return response()->json($this->repository->add($request), 201);
    }

    public function show(int $series)
    {
        $seriesModel = Serie::with('seasons.episodes')->find($series);
        if ($seriesModel === null) {
            return response()->json(['message' => "Series not found"], 404);
        }
        return $seriesModel;
    }

    public function update(Serie $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        $series->save();

        return $series;
    }

    public function destroy(int $series, Authenticatable $user)
    {
        dd($user->tokenCan('is_adim'));
        Serie::destroy($series);
        return response()->noContent();
    }
}
