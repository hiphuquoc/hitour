<?php

return [
    'seo'           => [
        'seo.id as id_seo',
        'seo.title', 
        'seo.description',
        'seo.image',
        'seo.image_small',
        'seo.level',
        'seo.parent',
        'seo.seo_title',
        'seo.seo_description',
        'seo.slug', 
        'seo.rating_aggregate_star',
        'seo.rating_aggregate_count',
        'seo.created_at',
        'seo.updated_at'
    ],
    'tour_location' => [
        'tour_location.id as id_tourlocation',
        'tour_location.name',
        'tour_location.note'
    ],
    'tour_info' => [
        'tour_info.id as id_tour',
        'tour_info.code',
        'tour_info.name',
        'tour_info.price_show',
        'tour_info.price_del',
        'tour_info.days',
        'tour_info.nights',
        'tour_info.pick_up',
        'tour_info.status_show',
        'tour_info.status_sidebar',
    ]
];