<?php

namespace App\Http\Controllers;

use App\Events\SeriesCreated as EventsSeriesCreated;
use App\Http\Middleware\Autenticador;
use App\Repositories\EloquentSeriesRepository;
use App\Http\Requests\SeriesFormRequest;
use App\Jobs\DeleteSeriesCover;
use App\Mail\SeriesCreated;
use App\Models\Episode;
use App\Models\Season;
use App\Models\Serie;
use App\Models\User;
use App\Repositories\SeriesRepository;
use DateTime;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class SeriesController extends Controller
{
    public function __construct(private SeriesRepository $repository)
    {
        $this->middleware(Autenticador::class)->except('index');
    }
    public function index(Request $request)
    {
        $series = Serie::all();
        //Se não usar um queryBuolder
        //$series = Serie::query()->orderBy('nome')->get();
        $mensagemSucesso = session('mensagem.sucesso');

        //tirar essa linha quando estiver usand flash
        //$request->session()->forget('mensagem.sucesso');


        /*return view('listar-series', [
            'series' => $series
        ]);*/
        return view('series.index')->with('series', $series)->with('mensagemSucesso', $mensagemSucesso);
    }

    public function create()
    {
        return view('series.create');
    }

    public function store(SeriesFormRequest $request)
    {
        
        $coverPath = $request->hasFile('cover') ? $request->file('cover')->store('series_cover', 'public') : 'null';
        $request->coverPath = $coverPath;
        $serie = $this->repository->add($request);
        EventsSeriesCreated::dispatch(
            $serie->nome,
            $serie->id,
            $request->seasonsQtdy,
            $request->episodesPerSeason
        );

        /*$serie = DB::transaction(function () use ($request) {
            $serie = Serie::create($request->all());
            $seasons = [];
            for ($i = 1; $i <= $request->seasonsQtdy; $i++) {
                $seasons[] = [
                    'series_id' => $serie->id,
                    'number' => $i,
                ];
            }
            Season::insert($seasons);


            $episodes = [];
            foreach ($serie->seasons as $season) {
                for ($j = 1; $j <= $request->episodesPerSeason; $j++) {
                    $episodes[] = [
                        'season_id' => $season->id,
                        'number' => $j
                    ];
                }
            }
            Episode::insert($episodes);

            return $serie;
        });*/

        //usando o Request
        /*$request->validate([
            'nome' => ['required','min:3']
        ]);*/


        //$nomeSerie = $request->input('nome');

        //session()->flash('mensagem.sucesso',"Série '{$serie->nome}' adicionada com sucesso");


        /*$nomeSerie = $request->nome;
        $serie = new Serie();
        $serie->nome = $nomeSerie;
        $serie->save();*/

        //return redirect("/series");
        //return redirect()->route('series.index');
        //Laravel versão 9
        return to_route('series.index')->with('mensagem.sucesso', "Série '{$serie->nome}' adicionada com sucesso");
    }

    public function destroy(Serie $series, Request $request)
    {

        $series->delete();
        DeleteSeriesCover::dispatch($series->cover);
        //Serie::destroy($series);
        //$request->session()->put('mensagem.sucesso', 'Série removida com sucesso');

        //para usar flash e desabilitar intelephense.diagnostics.undefinedMethods
        //que fica em Arquivo>Preferências>Configurações
        //$request->session()->flash('mensagem.sucesso', 'Série removida com sucesso');
        //Ou
        //session()->flash('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso");

        return to_route('series.index')->with('mensagem.sucesso', "Série '{$series->nome}' removida com sucesso");
    }

    public function edit(Serie $series)
    {
        return view('series.edit')->with('serie', $series);
    }

    public function update(Serie $series, SeriesFormRequest $request)
    {
        $series->fill($request->all());
        //$series->nome = $request->nome;
        $series->save();

        return to_route('series.index')
            ->with('mensagem.sucesso', "Série '{$series->nome}' atualizada com sucesso");
    }
}
