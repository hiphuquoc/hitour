@if(!empty($data)&&$data->isNotEmpty())
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "FAQPage",
            "mainEntity": [
                @foreach($data as $faq)
                    @if($loop->index!=0) 
                        ,
                    @endif
                    {
                        "@type": "Question",
                        "name": "{{ $faq->question ?? null }}",
                        "acceptedAnswer": {
                            "@type": "Answer",
                            "text": "{{ $faq->answer ?? null }}"
                        }
                    }
                @endforeach
            ]
        }
    </script>
@endif