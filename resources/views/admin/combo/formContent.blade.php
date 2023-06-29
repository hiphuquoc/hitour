@if(!empty($item->contents)&&$item->contents->isNotEmpty())
    @foreach($item->contents as $content)
        <div class="card" data-repeater-item>
            <div class="card-header border-bottom">
                <h4 class="card-title">
                    Nội dung
                    <i class="fa-solid fa-circle-xmark" data-repeater-delete></i>
                </h4>
            </div>
            <div class="card-body">

                <div class="formBox">
                    <div class="formBox_full">
                        <!-- One Row -->
                        <div class="formBox_full_item">
                            <label class="form-label inputRequired" for="name">Tiêu đề</label>
                            <textarea class="form-control" name="name" rows="3" required>{{ old('name') ?? $content->name ?? '' }}</textarea>
                        </div>
                        <!-- One Row -->
                        <div class="formBox_full_item">
                            <label class="form-label inputRequired" for="content">Nội dung</label>
                            <textarea class="form-control" name="content" rows="5" required>{{ old('content') ?? $content->content ?? '' }}</textarea>
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
                Nội dung
                <i class="fa-solid fa-circle-xmark" data-repeater-delete></i>
            </h4>
        </div>
        <div class="card-body">

            <div class="formBox">
                <div class="formBox_full">
                    <!-- One Row -->
                    <div class="formBox_full_item">
                        <label class="form-label inputRequired" for="name">Tiêu đề</label>
                        <textarea class="form-control" name="name" rows="3" required></textarea>
                    </div>
                    <!-- One Row -->
                    <div class="formBox_full_item">
                        <label class="form-label inputRequired" for="content">Nội dung</label>
                        <textarea class="form-control" name="content" rows="5" required></textarea>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
@endif