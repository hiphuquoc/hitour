<div class="formBox_full">
    <!-- One Row -->
    <div class="formBox_full_item">
        <label class="form-label inputRequired" for="name">Tên Phòng</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $data['name'] ?? null }}" required />
    </div>
    <!-- One Row -->
    <div class="formBox_full_item">
        <label class="form-label" style="margin-right:0.5rem;">Ảnh phòng</label>
        <input type="file" name="images[]" onchange="uploadFileAjax(this, 398, '54-hinh-nen-dien-thoai-wallpaper-mobile-meo-de-thuong');" multiple>
        <div class="uploadImageBox">
            <div class="uploadImageBox_box js_readURLsCustom_idWrite" style="position:relative;">
                @if(!empty($data['images']))
                    @foreach($data['images'] as $image)
                        <div id="randomIdImage_{{ $loop->index }}" class="uploadImageBox_box_item">
                            <input type="hidden" name="images[]" value="{{ $image->image_small ?? $image }}" />
                            <img src="{{ $image->image_small ?? $image }}">
                            <div class="uploadImageBox_box_item_icon" onclick="removeUpploadImage('randomIdImage_{{ $loop->index }}');"></div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <!-- One Row -->
    <div class="formBox_full_item"> 
        <label class="form-label" for="condition">Điều khoản (html)</label>
        <textarea class="form-control" id="condition"  name="condition" rows="5">{{ old('condition') ?? '' }}</textarea>
    </div>
    <!-- One Row -->
    <div class="formBox_full_item">
        <div class="flexBox">
            <div class="flexBox_item">
                <label class="form-label inputRequired" for="size">Kích thước</label>
                <input type="number" min="0" id="size" class="form-control" name="size" placeholder="0" value="{{ $data['size'] ?? '' }}" required />
            </div>
            <div class="flexBox_item" style="margin-left:1rem;">
                <label class="form-label inputRequired" for="number_people">Người tối đa</label>
                <input type="number" min="0" id="number_people" class="form-control" name="number_people" placeholder="0" value="{{ $data['number_people'] ?? '' }}" required />
            </div>
        </div>
    </div>
    <!-- One Row -->
    <div class="formBox_full_item">
        <label class="form-label inputRequired" for="price">Giá phòng /đêm</label>
        <input type="number" min="0" id="price" class="form-control" name="price" placeholder="0" value="{{ $data['price'] ?? '' }}" required />
    </div>
    <!-- One Row -->
    <div class="formBox_full_item">
        <label class="form-label inputRequired" for="facilities">Tiện nghi phòng</label>
        <select class="select2 form-select select2-hidden-accessible" id="facilities" name="facilities[]" multiple>
            @if(!empty($roomFacilities))
                @foreach($roomFacilities as $roomFacility)
                    @php
                        $selected = '';
                        if(!empty($data['facilities'])){
                            foreach($data['facilities'] as $r){
                                if($r['id']==$roomFacility->id){
                                    $selected = 'selected';
                                    break;
                                }
                            }
                        }
                    @endphp
                    <option value="{{ $roomFacility->id }}" {{ $selected }}>{{ $roomFacility->name }}</option>
                @endforeach
            @endif
        </select>
    </div>
</div>
<div class="formBox_full">
    <!-- One Row -->
    <div data-repeater-list="repeater">
        
        @if(!empty($data['details']))
            @foreach($data['details'] as $detail)
                <div class="card" data-repeater-item>
                    <div class="card-header border-bottom">
                        <h4 class="card-title">
                            Mô tả tiện nghi
                            <i class="fa-solid fa-circle-xmark" data-repeater-delete></i>
                        </h4>
                    </div>
                    <div class="card-body">
            
                        <div class="formBox">
                            <div class="formBox_full">
                                <!-- One Row -->
                                <div class="formBox_full_item">
                                    <label class="form-label">Loại</label>
                                    <input type="text" class="form-control" name="details[{{ $loop->index }}][name]" value="{{ $detail['name'] ?? null}}" />
                                </div>
                                <!-- One Row -->
                                <div class="formBox_full_item">
                                    <label class="form-label">Nội dung (html)</label>
                                    <textarea class="form-control" name="details[{{ $loop->index }}][detail]" rows="5">{{ $detail['detail'] ?? null}}</textarea>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            @endforeach
        @else
            <div class="card" data-repeater-item>
                <div class="card-header border-bottom">
                    <h4 class="card-title">
                        Mô tả tiện nghi
                        <i class="fa-solid fa-circle-xmark" data-repeater-delete></i>
                    </h4>
                </div>
                <div class="card-body">
        
                    <div class="formBox">
                        <div class="formBox_full">
                            <!-- One Row -->
                            <div class="formBox_full_item">
                                <label class="form-label">Loại</label>
                                <input type="text" class="form-control" name="details[0][name]" value="" />
                            </div>
                            <!-- One Row -->
                            <div class="formBox_full_item">
                                <label class="form-label">Nội dung (html)</label>
                                <textarea class="form-control" name="details[0][content]" rows="5"></textarea>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        @endif

    </div>
    <!-- One Row -->
    <div class="formBox_full_item" style="text-align:right;">
        <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            <span>Thêm</span>
        </button>
    </div>
</div>
<script type="text/javascript">
    $('.select2').select2();
    
    function removeUpploadImage(idNode){
        $('#'+idNode).remove();
    }
</script>