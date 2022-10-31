<div class="repeater formBox">
    <div class="formBox_full">
        <div data-repeater-list="question_answer">
            @if(!empty($item->questions)&&$item->questions->isNotEmpty())
                @foreach($item->questions as $question)
                <div class="flexBox" style="flex-wrap:unset;align-items:flex-start;" data-repeater-item>
                    <div class="flexBox_item">
                        <!-- One Row -->
                        <div class="formBox_full_item">
                            <div class="flexBox" style="justify-content:space-between;">
                                <label class="form-label">Câu hỏi</label>
                            </div>
                            <textarea class="form-control" name="question_answer[{{ $loop->index }}][question]" rows="3">{{ $question->question ?? null }}</textarea>
                        </div>
                    </div>
                    <div class="flexBox_item">
                        <!-- One Row -->
                        <div class="formBox_full_item">
                            <div class="flexBox" style="justify-content:space-between;">
                                <label class="form-label">Trả lời</label>
                            </div>
                            <textarea class="form-control" name="question_answer[{{ $loop->index }}][answer]" rows="3">{{ $question->answer ?? null }}</textarea>
                        </div>
                    </div>
                    <div class="flexBox_item btnRemoveRepeater" style="align-self:center;" data-repeater-delete>
                        <i class="fa-solid fa-xmark"></i>
                    </div>
                </div>
                @endforeach
            @else 
            <div class="flexBox" style="flex-wrap:unset;align-items:flex-start;" data-repeater-item>
                <div class="flexBox_item">
                    <!-- One Row -->
                    <div class="formBox_full_item">
                        <div class="flexBox" style="justify-content:space-between;">
                            <label class="form-label">Câu hỏi</label>
                        </div>
                        <textarea class="form-control" name="question" rows="3">{{ null }}</textarea>
                    </div>
                </div>
                <div class="flexBox_item">
                    <!-- One Row -->
                    <div class="formBox_full_item">
                        <div class="flexBox" style="justify-content:space-between;">
                            <label class="form-label">Trả lời</label>
                        </div>
                        <textarea class="form-control" name="answer" rows="3">{{ null }}</textarea>
                    </div>
                </div>
                <div class="flexBox_item btnRemoveRepeater" style="align-self:center;" data-repeater-delete>
                    <i class="fa-solid fa-xmark"></i>
                </div>
            </div>
            @endif
        </div>
        <div style="margin-top:1.2rem;text-align:right;"> 
            <button class="btn btn-icon btn-primary waves-effect waves-float waves-light" type="button" aria-label="Thêm" data-repeater-create>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus me-25"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                <span>Thêm</span>
            </button>
        </div>
    </div>
</div>
@push('scripts-custom')
    <script type="text/javascript">
        $('.repeater').repeater();
    </script>
@endpush