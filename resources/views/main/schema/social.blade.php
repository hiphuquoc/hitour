@php
    $title          = $data->seo_title ?? $data->title ?? config('main.description');
    $description    = $data->seo_description ?? $data->description ?? null;
    $url            = !empty($data->link_canonical) ? $data->link_canonical : $data->slug_full;
    $urlFull        = env('APP_URL').'/'.$url;
    $image          = !empty($data->image) ? env('APP_URL').$data->image : env('APP_URL').config('admin.images.default_750x460');
@endphp
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}" />
<link rel="canonical" href="{{ $urlFull }}" />
<meta property="og:locale" content="vi_VN" />
<meta property="og:title" content="{{ $title }}" />
<meta property="og:description" content="{{ $description }}" />
<meta property="og:url" content="{{ $urlFull }}" />
<meta property="og:site_name" content="{{ $title }}" />
<meta property="article:published_time" content="{{ date('c', strtotime($data->created_at)) }}" />
<meta property="article:modified_time" content="{{ date('c', strtotime($data->updated_at)) }}" />
<meta property="og:image" content="{{ $image }}" />
<meta property="og:image:width" content="750" />
<meta property="og:image:height" content="460" />
<meta property="og:image:alt" content="{{ $title }}" />
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title }}" />
<meta name="twitter:description" content="{{ $description }}" />
<meta name="twitter:creator" content="@hitour" />
<meta name="twitter:image" content="{{ $image }}" />