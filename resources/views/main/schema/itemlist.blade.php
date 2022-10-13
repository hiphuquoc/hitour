@if(!empty($data)&&$data->isNotEmpty())
    @php
        $xhtml          = null;
        $i              = 1;
        foreach($data as $d){
            if(!empty($d->seo->slug_full)){
                if($i!=1) $xhtml .= ', ';
                $xhtml  .= '{
                            "@type": "ListItem",
                            "position": '.$i.',
                            "url": "'.env('APP_URL').'/'.$d->seo->slug_full.'"
                        }';
                ++$i;
            }
        }
    @endphp
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "ItemList",
            "itemListElement": [
                {!! $xhtml !!}
            ]
        }
    </script>
@endif