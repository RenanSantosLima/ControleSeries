<?php

namespace Tests\Feature;

use App\Http\Requests\SeriesFormRequest;
use App\Repositories\SeriesRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeriesRepositoryTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_when_a_series_is_created_its_seasons_and_episodes_must_also_be_created(): void
    {
        //Arrange (prepara o cenario de teste)
        /** @var SeriesRepository $repository */
        $repository = $this->app->make(SeriesRepository::class);
        $request = new SeriesFormRequest();
        $request->nome = 'Nome da série';
        $request->seasonsQtdy = 1;
        $request->episodesPerSeason = 1;

        // Act (executa os testes)
        $repository->add($request);

        // Assert (verificação)
        $this->assertDatabaseHas('series', ['nome' => 'Nome da série']);
        $this->assertDatabaseHas('seasons', ['number' => 1]);
        $this->assertDatabaseHas('episodes', ['number' => 1]);
    }
}
