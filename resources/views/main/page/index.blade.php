@extends('main.layouts.main')
@push('head-custom')
<!-- ===== START:: SCHEMA ===== -->
@php
    $dataSchema = $item->seo ?? null;
@endphp

<!-- STRAT:: Title - Description - Social -->
@include('main.schema.social', ['data' => $dataSchema])
<!-- END:: Title - Description - Social -->

<!-- STRAT:: Organization Schema -->
@include('main.schema.organization')
<!-- END:: Organization Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.article', ['data' => $dataSchema])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.creativeworkseries', ['data' => $dataSchema])
<!-- END:: Article Schema -->

<!-- STRAT:: Article Schema -->
@include('main.schema.breadcrumb', ['data' => $breadcrumb])
<!-- END:: Article Schema -->

<!-- ===== END:: SCHEMA ===== -->
@endpush
@section('content')

    @include('main.snippets.breadcrumb')

    <div class="sectionBox">
        <div class="container">
            <!-- title -->
            <h1 class="titlePage">{{ $item->name ?? null }}</h1>
            {{-- <!-- rating -->
            @include('main.template.rating', compact('item')) --}}
            {{-- <!-- tocContent main -->
            <div id="tocContentMain" style="margin-top:1rem;"></div> --}}
            <!-- content -->
            {!! $content ?? null !!}
        </div>
    </div>

    @if(!empty($item->show_partner)&&$item->show_partner==1)
        <!-- START: Đối tác tàu cao tốc -->
        @if(!empty($shipPartners)&&$shipPartners->isNotEmpty())
            <div class="sectionBox withBorder">
                <div class="container">
                    @include('main.home.partner', [
                        'list'          => $shipPartners,
                        'title'         => 'Đối tác tàu cao tốc',
                        'description'   => 'Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ'
                    ])
                </div>
            </div>
        @endif
        <!-- END: Đối tác tàu cao tốc -->

        <!-- START: Đối tác máy bay -->
        @if(!empty($airPartners)&&$airPartners->isNotEmpty())
            <div class="sectionBox withBorder">
                <div class="container">
                    @include('main.home.partner', [
                        'list'          => $airPartners,
                        'title'         => 'Đối tác máy bay',
                        'description'   => 'Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ Nếu cần phương tiện đưa đón, di chuyển và tham quan bạn có thể tham khảo thêm dịch vụ'
                    ])
                </div>
            </div>
        @endif
        <!-- END: Đối tác máy bay -->
    @endif

@endsection
@push('scripts-custom')
    <script type="text/javascript">
        // buildTocContentMain('js_buildTocContentSidebar_element');
    </script>
@endpush