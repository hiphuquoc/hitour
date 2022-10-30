<div class="megaMenu">
    <div class="megaMenu_title" style="max-width:240px;">
    <ul>
        @if(!empty($dataTourContinent)&&$dataTourContinent->isNotEmpty())
            @foreach($dataTourContinent as $tourContinent)
                <li id="menuTourContinent_{{ $loop->index }}" onmouseover="openMegaMenuTourContinent(this.id);" {{ $loop->index==0 ? 'class=selected' : null }}>
                    <a href="/{{ $tourContinent->seo->slug_full ?? null }}">Tour {{ $tourContinent->display_name }}<i class="fas fa-angle-right"></i></a>
                </li>
            @endforeach
        @endif 
    </ul>
    </div>
    <div class="megaMenu_content">
        <!-- Danh sách Tour -->
        @foreach($dataTourContinent as $tourContinent)
            <ul data-menu-tourcontinent="menuTourContinent_{{ $loop->index }}">
                @foreach($tourContinent->tourCountries as $tourCountry)
                    @if($loop->index==0||$loop->index%7==0)
                        <li>
                            <ul>
                    @endif
                    <li><a href="/{{ $tourCountry->seo->slug_full ?? null }}">{{ $tourCountry->name ?? $tourCountry->seo->title ?? null }}</a></li>
                    @if(($loop->index+1)%7==0||($loop->index+1)==$tourContinent->tourCountries->count())
                            </ul>
                        </li>
                    @endif
                @endforeach
            </ul>
        @endforeach       
    </div>
</div>
@push('scripts-custom')
    <script type="text/javascript">
        function openMegaMenuTourContinent(id){
            var elemt	= $('#'+id);
            elemt.siblings().removeClass('selected');
            elemt.addClass('selected');
            $('[data-menu-tourcontinent]').each(function(){
                var key	= $(this).attr('data-menu-tourcontinent');
                if(key==id){
                $(this).css('display', 'flex');
                }else {
                    $(this).css('display', 'none');
                }
            });
        }
    </script>
@endpush