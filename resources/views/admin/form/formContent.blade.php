<div class="formBox">
    <div class="formBox_full">

        <!-- One Column -->
        <textarea class="form-control" id="content"  name="content" rows="20">{{ old('content') ?? $content ?? '' }}</textarea>

    </div>
</div>

@push('scripts-custom')
    {{-- @include('admin.script.tiny', ['id' => 'content']) --}}
@endpush