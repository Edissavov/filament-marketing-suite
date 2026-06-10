{{-- Default landing page layout. Publish the package views to customize. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @if (! empty($seoDescription))
        <meta name="description" content="{{ $seoDescription }}">
    @endif
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    @if (! empty($seoDescription))
        <meta property="og:description" content="{{ $seoDescription }}">
    @endif
    @if (! empty($seoImage))
        <meta property="og:image" content="{{ $seoImage }}">
    @endif
    {{ $structuredData ?? '' }}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-900 antialiased">
    {{ $slot }}
</body>
</html>
