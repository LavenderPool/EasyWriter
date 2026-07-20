<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="robots" content="noindex,nofollow">
    <title>{{ $manga->title }} — {{ config('app.name') }}</title>
    <link rel="stylesheet" href="{{ asset('css/reader.css') }}">
</head>
<body class="reader-body">
    <header class="reader-header" id="readerHeader">
        <div class="reader-title">
            <strong>{{ $manga->title }}</strong>
            <span id="pageStatus">Page 1 / {{ $totalPages }}</span>
        </div>
        <button type="button" class="icon-btn" id="toggleUi" aria-label="Toggle controls">☰</button>
    </header>

    <main class="reader-stage" id="readerStage">
        <button type="button" class="tap-zone tap-prev" id="tapPrev" aria-label="Previous page"></button>
        <div class="page-frame" id="pageFrame">
            <img id="pageImage" alt="Current page" draggable="false">
            <div class="page-loader" id="pageLoader">Loading…</div>
        </div>
        <button type="button" class="tap-zone tap-next" id="tapNext" aria-label="Next page"></button>
    </main>

    <footer class="reader-footer" id="readerFooter">
        <button type="button" class="nav-btn" id="prevBtn">Previous</button>
        <div class="progress-wrap">
            <input type="range" id="pageSlider" min="1" max="{{ $totalPages }}" value="1" aria-label="Page slider">
            <div class="progress-meta">
                <span id="progressText">1 / {{ $totalPages }}</span>
                <button type="button" class="text-btn" id="jumpBtn">Go to page</button>
            </div>
        </div>
        <button type="button" class="nav-btn" id="nextBtn">Next</button>
    </footer>

    <script>
        window.READER_DATA = {
            mangaId: {{ $manga->id }},
            progressKey: @json($progressKey),
            totalPages: {{ $totalPages }},
            pages: @json($pages),
        };
    </script>
    <script src="{{ asset('js/reader.js') }}"></script>
</body>
</html>
