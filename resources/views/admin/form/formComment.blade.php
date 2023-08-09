@if(!empty($comments))
@foreach($comments as $comment)
    <div class="pageAdminWithRightSidebar_main_content_item card" data-repeater-item>
        <div class="card-header border-bottom">
            <h4 class="card-title">
                Comment
                <i class="fa-solid fa-circle-xmark" data-repeater-delete></i>
            </h4>
        </div>
        <div class="card-body">
            <div class="formBox" style="display:flex;flex-wrap:unset;align-items:flex-start;">
                <div class="formBox_full" style="width:100%;">
                    <div class="formBox_full_item">
                        <label class="form-label">Tiêu đề</label>
                        <input class="form-control" name="title" value="{{ $comment['title'] ?? null }}" />
                    </div>
                    <div class="formBox_full_item">
                        <label class="form-label">Nội dung comment</label>
                        <textarea class="form-control" name="comment" rows="3">{{ $comment['comment'] ?? null }}</textarea>
                    </div>
                    <div class="formBox_full_item">
                        <div class="flexBox">
                            <div class="flexBox_item">
                                <label class="form-label">Người comment</label>
                                <input class="form-control" name="author_name" value="{{ $comment['author_name'] ?? null }}" />
                            </div>
                            <div class="flexBox_item">
                                <label class="form-label">Số điện thoại</label>
                                <input class="form-control" name="author_phone" value="{{ $comment['author_phone'] ?? null }}" />
                            </div>
                        </div>
                    </div>
                    <div class="formBox_full_item">
                        <div class="flexBox">
                            <div class="flexBox_item">
                                <label class="form-label">Email</label>
                                <input class="form-control" name="author_email" value="{{ $comment['author_email'] ?? null }}" />
                            </div>
                            <div class="flexBox_item">
                                @php
                                    $rating     = 0;
                                    if(!empty($comment['rating'])) $rating = $comment['rating']*2;
                                @endphp
                                <label class="form-label">Rating</label>
                                <input type="range" class="form-range mt-1" name="rating" min="0" max="10" value="{{ $rating }}" />
                            </div>
                        </div>
                    </div>
                    <div class="formBox_full_item">
                        <label class="form-label">Ngày comment</label>
                        <input class="form-control" type="date" name="created_at" value="{{ !empty($comment['created_at']) ? date('Y-m-d', strtotime($comment['created_at'])) : time() }}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endforeach
@endif

<div class="pageAdminWithRightSidebar_main_content_item card" data-repeater-item>
    <div class="card-header border-bottom">
        <h4 class="card-title">
            Comment
            <i class="fa-solid fa-circle-xmark" data-repeater-delete></i>
        </h4>
    </div>
    <div class="card-body">
        <div class="formBox" style="display:flex;flex-wrap:unset;align-items:flex-start;">
            <div class="formBox_full" style="width:100%;">
                <div class="formBox_full_item">
                    <label class="form-label">Tiêu đề</label>
                    <input class="form-control" name="title" value="" />
                </div>
                <div class="formBox_full_item">
                    <label class="form-label">Nội dung comment</label>
                    <textarea class="form-control" name="comment" rows="3"></textarea>
                </div>
                <div class="formBox_full_item">
                    <div class="flexBox">
                        <div class="flexBox_item">
                            <label class="form-label">Người comment</label>
                            <input class="form-control" name="author_name" value="" />
                        </div>
                        <div class="flexBox_item">
                            <label class="form-label">Số điện thoại</label>
                            <input class="form-control" name="author_phone" value="" />
                        </div>
                    </div>
                </div>
                <div class="formBox_full_item">
                    <div class="flexBox">
                        <div class="flexBox_item">
                            <label class="form-label">Email</label>
                            <input class="form-control" name="author_email" value="" />
                        </div>
                        <div class="flexBox_item">
                            <label class="form-label">Rating</label>
                            <input type="range" class="form-range mt-1" name="rating" min="0" max="10" value="0" />
                        </div>
                    </div>
                </div>
                <div class="formBox_full_item">
                    <label class="form-label">Ngày comment</label>
                    <input class="form-control" type="date" name="created_at" value="{{ time() }}" />
                </div>
            </div>
        </div>
    </div>
</div>