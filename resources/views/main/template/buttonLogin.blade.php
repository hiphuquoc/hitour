@if(!empty($user))
    <div class="loginBox" onClick="toggleModalCustomerLoginForm('modalLoginFormCustomerBox');">
        <div class="loginBox_iconAvatar">
            <img src="{{ Storage::url('images/svg/icon-user.svg') }}" alt="thông tin tài khoản" title="thông tin tài khoản" />
        </div>
        <div class="maxLine_1">{{ $user->name ?? 'Tài khoản' }}</div>
        <div class="loginBox_list">
            {{-- <div class="loginBox_list_item">
                <i class="fa-solid fa-key"></i>
                <div>Đổi mật khẩu</div>
            </div>  --}}
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
                    <div class="maxLine_1">Logout</div>
                @else 
                    <div class="maxLine_1">Đăng xuất</div>
                @endif
            </a>
        </div>
        <div class="loginBox_background">

        </div>
    </div>
@else 
    <div class="loginBox" onClick="toggleModalCustomerLoginForm('modalLoginFormCustomerBox');">
        <img src="{{ Storage::url('images/svg/sign-in-alt.svg') }}" alt="đăng nhập {{ config('main.company_name') }}" title="đăng nhập {{ config('main.company_name') }}" />
        @if(!empty($language)&&$language=='en')
            <div class="maxLine_1">Login</div>
        @else 
            <div class="maxLine_1">Đăng nhập</div>
        @endif
    </div>
@endif