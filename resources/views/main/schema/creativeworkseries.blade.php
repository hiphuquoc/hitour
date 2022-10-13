<script type="application/ld+json">
    {
        "@context": "https://schema.org/",
        "@type": "CreativeWorkSeries",
        "name": "{{ $data->seo_title ?? $data->title ?? null }}",
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "{{ $data->rating_aggregate_star ?? '5' }}",
            "bestRating": "5",
            "ratingCount": "{{ $data->rating_aggregate_count ?? '120' }}"
        }
    }
</script>