@php
	$imageSlider 		= 'linear-gradient(-180deg, rgba(0, 123, 255, 0.5), rgb(0, 90, 180))';
	foreach($item->files as $file){
		if($file->file_type=='slider') $imageSlider = 'url('.$file->file_path.') center center';
	}
@endphp
<!-- background slider -->
<div class="bookOnline" style="background: {{ $imageSlider }}"></div>
<!-- Booking form -->
@include('main.form.formBooking', compact('active'))