<!DOCTYPE html>
<html>
<head>
    <title>Test Simple</title>
</head>
<body>
    <h1>{{ __('Simple Test View') }}</h1>
    <p>{{ __('Number of articles') }}: {{ $articles->count() }}</p>

    @foreach($articles as $article)
        <div>
            <h3>{{ $article->title }}</h3>
            <p>{{ __('Category') }}: {{ $article->category ? $article->category->name : __('None') }}</p>
        </div>
    @endforeach
</body>
</html>
