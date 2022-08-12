<div class="formBox">
    <div class="formBox_full">
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="tour_timetable_title">Tiêu đề</label>
            <textarea class="form-control" name="tour_timetable_title" rows="3" required>{{ old('tour_timetable_title') ?? $item->title ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label inputRequired" for="tour_timetable_content">Nội dung</label>
            <textarea class="form-control" name="tour_timetable_content" rows="5" required>{{ old('tour_timetable_content') ?? $item->content ?? '' }}</textarea>
        </div>
        <!-- One Row -->
        <div class="formBox_full_item">
            <label class="form-label" for="tour_timetable_content_sort">Nội dung rút gọn</label>
            <textarea class="form-control" name="tour_timetable_content_sort" rows="5">{{ old('tour_timetable_content') ?? $item->content_sort ?? '' }}</textarea>
        </div>
    </div>
</div>