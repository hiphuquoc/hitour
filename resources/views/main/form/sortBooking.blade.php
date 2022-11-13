@php
	$imageSlider 		= 'linear-gradient(-180deg, rgba(0, 123, 255, 0.5), rgb(0, 90, 180))';
	if(!empty($item->files)&&$item->files->isNotEmpty()){
		foreach($item->files as $file){
			if($file->file_type=='slider') $imageSlider = 'url('.$file->file_path.') center center';
		}
	}
@endphp
<!-- background slider -->
<div id="js_setHeightBox_box" class="bookOnline" style="background: {{ $imageSlider }}"></div>
<!-- Booking form -->
@include('main.form.formBooking', compact('active'))
@push('scripts-custom')
	<script type="text/javascript">
		setHeightBox('js_setHeightBox_box', 0.15625);
        $(window).resize(function(){
            setHeightBox('js_setHeightBox_box', 0.15625);
        });
		function setHeightBox(idBox, ratio){
            const valueWidth    = $('#'+idBox).innerWidth();
            const valueHeight   = parseInt(valueWidth)*ratio;
            $('#'+idBox).css('height', valueHeight+'px');
        }
	</script>
@endpush