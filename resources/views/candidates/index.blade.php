<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote en ligne</title>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body>

<!-- Bannière et Slide -->
<div class="banner">
    <h1>Bienvenue à l'événement de vote</h1>
    <!-- Ajoutez ici votre slide d'images -->
</div>

<!-- Cartes des candidats -->
<div class="card-container">
    @foreach($candidates as $candidate)
    <div class="card">
        <div class="card-top">
            <span class="candidate-number">{{ $candidate->id }}</span>
        </div>
        <div class="card-image">
            <img src="{{ asset('images/' . $candidate->image) }}" alt="{{ $candidate->name }}">
        </div>
        <div class="card-bottom">
            <span class="candidate-name">{{ $candidate->name }}</span>
            <span class="candidate-votes">{{ $candidate->votes }} votes</span>
        </div>
        <a href="{{ route('candidates.show', $candidate->id) }}" class="vote-button">Votez</a>
    </div>
    @endforeach
</div>

<!-- Footer -->
<footer>
    <p>Informations utiles ici</p>
</footer>

</body>
</html>
