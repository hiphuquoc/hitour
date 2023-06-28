@if(!empty($user))
    <div class="headerBottom_item" onClick="toggleMenuListMobile();">
        <div class="headerBottom_item_icon">
            <img src="{{ Storage::url('images/svg/icon-user.svg') }}" alt="mua ngay" title="mua ngay" style="width:22px;" />
        </div>
        <div class="headerBottom_item_text maxLine_1">
            {{ $user->name ?? 'Tài khoản' }}


            <div class="headerBottom_item_text_modal">
                {{-- <a href="#" class="loginBox_list_item">
                    <i class="fa-solid fa-download"></i>
                    @if(!empty($language)&&$language=='en')
                        <div>My Downloads</div>
                    @else 
                        <div>Tải xuống của tôi</div>
                    @endif
                </a>  --}}
                <a href="{{ route('admin.logout') }}" class="loginBox_list_item">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    @if(!empty($language)&&$language=='en')
                        <div>Logout</div>
                    @else 
                        <div>Đăng xuất</div>
                    @endif
                </a>
            </div>
            <div class="headerBottom_item_text_background"></div>
            <script type="text/javascript">
                function toggleMenuListMobile(){
                    const flagShow = $('.headerBottom_item_text_modal').css('display');
                    if(flagShow=='none'){
                        $('.headerBottom_item_text_modal').css('display', 'block');
                        $('.headerBottom_item_text_background').css('display', 'block');
                    }else {
                        $('.headerBottom_item_text_modal').css('display', 'none');
                        $('.headerBottom_item_text_background').css('display', 'none');
                    }
                }

            </script>
        </div>
    </div>
@else 
    <div class="headerBottom_item" onClick="toggleModalCustomerLoginForm('modalLoginFormCustomerBox');">
        <div class="headerBottom_item_icon">
            <img src="{{ Storage::url('images/svg/sign-in-alt.svg') }}" alt="mua ngay" title="mua ngay" />
        </div>
        <div class="headerBottom_item_text">
            @if(!empty($language)&&$language=='en')
                Login
            @else 
                Đăng nhập
            @endif
        </div>
    </div>
@endif