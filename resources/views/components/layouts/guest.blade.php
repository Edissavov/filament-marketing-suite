{{-- Default guest layout (help center). Publish the package views to customize. --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @if (! empty($seoDescription))
        <meta name="description" content="{{ $seoDescription }}">
    @endif
    {{ $structuredData ?? '' }}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 antialiased">
    {{ $slot }}
</body>
</html>
