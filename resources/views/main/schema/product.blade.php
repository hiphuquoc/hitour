@php
    $images     = !empty($data->image) ? '"'.env('APP_URL').$data->image.'"' : null;
    $i          = 0;
    foreach($files as $file){
        $images .= ', ';
        $images .= '"'.env('APP_URL').$file->file_path.'"';
        ++$i;
    }
@endphp
<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "Product",
        "name": "{{ $data->seo_title ?? $data->title ?? null }}",
        "url": "{{ URL::current() }}",
        "image":
            [
                {!! $images !!}
            ],
        "description": "{{ $data->seo_description ?? $data->description ?? null }}",
        "sku": "WW202022M{{ !empty($data->created_at) ? strtotime($data->created_at) : 00 }}YK/VN",
        "brand": {
            "@type": "Brand",
            "name": "Hitour.vn"
        },
        "review":
            {
                "@type": "Review",
                "reviewRating":
                    {
                        "@type": "Rating",
                        "ratingValue": "5"
                    },
                "author": {
                    "@type": "Thing",
                    "name": "Hitour.vn"
                }
            },
        "aggregateRating":
            {
                "@type": "AggregateRating",
                "ratingValue": "{{ $data->rating_aggregate_star ?? '4.4' }}",
                "reviewCount": "{{ $data->rating_aggregate_count ?? '172' }}",
                "bestRating": "5"
            },
        "offers":
            {
                "@type": "AggregateOffer",
                "url": "{{ URL::current() }}",
                "offerCount": "1",
                "priceCurrency": "VND",
                "lowPrice": "{{ $lowPrice ?? '500000' }}",
                "highPrice": "{{ $highPrice ?? '5000000' }}",
                "itemCondition": "https://schema.org/UsedCondition",
                "availability": "https://schema.org/InStock",
                "seller":
                    {
                        "@type": "Organization",
                        "name": "Hitour.vn",
                        "url": "{{ env('APP_URL') }}"
                    }
            }
    }
</script>
