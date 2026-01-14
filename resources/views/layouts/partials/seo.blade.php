@php
    $defaultTitle = $settings->company_name ?? 'Bengawan Computer';
    $defaultDesc =
        $settings->about_desc ??
        'Solusi teknologi terlengkap di Solo Raya. Laptop baru & bekas, service profesional, dan pengadaan instansi.';
    $defaultImg = asset('assets/img/front-office.webp');
    $defaultKw = 'bengawan komputer, toko laptop solo, jual beli laptop bekas sukoharjo, service laptop kartasura';

    $seoTitle = $title ?? $defaultTitle;
    $seoDescription = isset($description)
        ? Str::limit(strip_tags($description), 160)
        : Str::limit(strip_tags($defaultDesc), 160);
    $seoImage = $image ?? $defaultImg;
    $seoKeywords = $keywords ?? $defaultKw;
    $seoType = $type ?? 'website';
    $seoUrl = url()->current();
@endphp

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>{{ $seoTitle }}</title>
<meta name="title" content="{{ $seoTitle }}">
<meta name="description" content="{{ $seoDescription }}">
<meta name="keywords" content="{{ $seoKeywords }}">
<meta name="robots" content="index, follow">
<meta name="revisit-after" content="7 days">
<meta name="language" content="Indonesian">
<meta name="author" content="{{ $settings->company_name ?? 'Bengawan Computer' }}">

<link rel="canonical" href="{{ $seoUrl }}">

<meta name="theme-color" content="#2D4CC8">
<meta name="msapplication-navbutton-color" content="#2D4CC8">
<meta name="apple-mobile-web-app-status-bar-style" content="#2D4CC8">

<meta property="og:type" content="{{ $seoType }}">
<meta property="og:url" content="{{ $seoUrl }}">
<meta property="og:site_name" content="{{ $settings->company_name ?? 'Bengawan Computer' }}">
<meta property="og:title" content="{{ $seoTitle }}">
<meta property="og:description" content="{{ $seoDescription }}">
<meta property="og:image" content="{{ $seoImage }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:image:alt" content="{{ $seoTitle }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:url" content="{{ $seoUrl }}">
<meta name="twitter:title" content="{{ $seoTitle }}">
<meta name="twitter:description" content="{{ $seoDescription }}">
<meta name="twitter:image" content="{{ $seoImage }}">

<link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/favicon/favicon-16x16.png') }}">
<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('assets/favicon/favicon-32x32.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/favicon/apple-touch-icon.png') }}">
<link rel="manifest" href="{{ asset('assets/favicon/site.webmanifest') }}">
