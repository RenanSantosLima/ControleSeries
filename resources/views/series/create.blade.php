<x-layout title="Nova Série">
    <form action="{{ route('series.store') }}" method="post" enctype="multipart/form-data">
        @csrf
    
        <div class="row mb-3">
            <div class="col-8">
                <label for="nome" class="form-label">Nome:</label>
                <input type="text"
                    autofocus
                    id="nome"
                    name="nome"
                    class="form-control"
                    value="{{ old('nome') }}">
            </div>

            <div class="col-2">
                <label for="seasonsQtdy" class="form-label">N* Temporadas:</label>
                <input type="text"
                    id="seasonsQtdy"
                    name="seasonsQtdy"
                    class="form-control"
                    value="{{ old('seasonsQtdy') }}">
            </div>

            <div class="col-2">
                <label for="episodesPerSeason" class="form-label">Eps / Temporada:</label>
                <input type="text"
                    id="episodesPerSeason"
                    name="episodesPerSeason"
                    class="form-control"
                    value="{{ old('episodesPerSeason') }}">
            </div>

        </div>

        <div class="row mb-3">
            <div class="col-12">
                <label for="cover" class="form-label">Capa</label>
                <input type="file" id="cover" name="cover" class="form-control" accept="image/gif, image/png, image/jpeg" />
            </div>
        </div>
    
        <button type="submit" class="btn btn-primary">Adicionar</button>
    </form>
      
</x-layout>