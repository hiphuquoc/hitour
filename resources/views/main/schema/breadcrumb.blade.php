@php
    $xhtml  = '{
                "@type": "ListItem",
                "position": 1,
                "name": "Trang chủ",
                "item": "'.env('APP_URL').'"
            }';
    $i      = 2;
    foreach($data as $d){
        $xhtml .= ', ';
        $title  = !empty($d->title) ? $d->title : $d->seo_title;
        $slug   = !empty($d->slug_full) ? $d->slug_full : null;
        $xhtml .= '{
                        "@type": "ListItem",
                        "position": '.$i.',
                        "name": "'.$title.'",
                        "item": "'.env('APP_URL').'/'.$slug.'"
                    }';
        ++$i;
    }
@endphp
<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": [
            {!! $xhtml !!}
        ]
    }
</script>
