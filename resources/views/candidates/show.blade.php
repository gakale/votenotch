<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $candidate->name }}</title>
    <style>
        /* Ajoutez ici votre CSS personnalisé */
        .candidate-card {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .candidate-card:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
<div class="candidate-card">
    <h1>Numéro du candidat : {{ $candidate->id }}</h1>
    <img src="{{ $candidate->photo}}" alt="{{ $candidate->name }}" width="200">
    <h2>Nom du candidat : {{ $candidate->name }}</h2>
    <p>Nombre de votes : {{ $candidate->votes_count }}</p>
    <p>Biographie : {{ $candidate->biography }}</p>
    <a href="{{ route('candidates.index') }}">Retour à la liste des candidats</a>
    <form action="{{ route('candidates.vote', $candidate->id) }}" method="POST">
        @csrf
        <button type="submit" class="vote-button">Voter</button>
    </form>
</div>

<script>
    // Ajoutez ici votre JavaScript personnalisé
</script>
</body>
</html>
