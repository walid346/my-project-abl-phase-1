<!DOCTYPE html>
<html>
<head>
    <title>Test Simple</title>
</head>
<body>
    <h1>Test Vue Simple</h1>
    <p>Nombre d'articles: {{ $articles->count() }}</p>
    
    @foreach($articles as $article)
        <div>
            <h3>{{ $article->title }}</h3>
            <p>Catégorie: {{ $article->category ? $article->category->name : 'Aucune' }}</p>
        </div>
    @endforeach
</body>
</html>
