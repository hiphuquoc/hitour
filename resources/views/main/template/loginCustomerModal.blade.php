<div id="modalLoginFormCustomerBox" class="modalLoginFormCustomerBox">
    <!-- modal background -->
    <div class="modalLoginFormCustomerBox_bg" onClick="toggleModalCustomerLoginForm('modalLoginFormCustomerBox');"></div>
    <!-- modal box -->
    <div class="modalLoginFormCustomerBox_box">
        <div class="modalLoginFormCustomerBox_box_left" style="background:url('{{ Storage::url('images/upload/banner-login-type-manager-upload.webp') }}') no-repeat;background-size: 100% 101%;">
        </div>
        
        <div class="modalLoginFormCustomerBox_box_right">
            <form id="formLogin" method="get" style="width:100%;">
            <div class="loginFormCustomer">
                <div class="loginFormCustomer_title">
                    @if(!empty($language)&&$language=='en')
                        Login {{ config('main.company_name') }}
                    @else 
                        Đăng nhập {{ config('main.company_name') }}
                    @endif
                </div>
                <div class="loginFormCustomer_body">
                    <div class="loginFormCustomer_body_item">
                        <!-- input -->
                        <div class="formBox">
                            <div class="formBox_item">
                                <div class="inputWithLabelInside">
                                    @if(!empty($language)&&$language=='en')
                                        <label for="email">Email login</label>
                                    @else 
                                        <label for="email">Email đăng nhập</label>
                                    @endif
                                    <input type="text" id="email" name="email" required />
                                </div>
                            </div>
                            <div class="formBox_item">
                                <div class="inputWithLabelInside">
                                    @if(!empty($language)&&$language=='en')
                                        <label for="password">Password</label>
                                    @else 
                                        <label for="password">Mật khẩu</label>
                                    @endif
                                    <input type="password" id="password" name="password" autocomplete="off" required />
                                </div>
                            </div>
                            <div class="formBox_item" style="display:flex;justify-content:space-between;align-item:flex-end;">
                                <label class="checkBox" for="remember">
                                    <input type="checkbox" id="remember" name="remember" />
                                    @if(!empty($language)&&$language=='en')
                                        <div>Remember me</div>
                                    @else 
                                        <div>Ghi nhớ tôi</div>
                                    @endif
                                </label>
                                <div id="noticeLogin" class="noticeLogin"> 
                                    <!-- thông báo đăng nhập -->
                                    {{-- Tên đăng nhập và mật khẩu không khớp! --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(!empty($language)&&$language=='en')
                        <div class="loginFormCustomer_body_item">
                            <!-- button -->
                            <button type="button" class="button" onClick="submitFormLogin('formLogin');">Login</div>
                        </div>
                        <div class="loginFormCustomer_body_item">
                            Don't have a password yet? <a href="#">Registry now</a>
                        </div>  
                    @else 
                        <div class="loginFormCustomer_body_item">
                            <!-- button -->
                            <button type="button" class="button" onClick="submitFormLogin('formLogin');">Đăng nhập</div>
                        </div>
                        <div class="loginFormCustomer_body_item">
                            Bạn chưa có mật khẩu? <a href="#">Đăng ký ngay</a>
                        </div>  
                    @endif
                    <div class="loginFormCustomer_body_item">
                        <!-- login social -->
                        <div class="loginFormSocial">
                            @if(!empty($language)&&$language=='en')
                                <div class="loginFormSocial_title">
                                    or login with
                                </div>
                            @else 
                                <div class="loginFormSocial_title">
                                    hoặc đăng nhập với
                                </div>
                            @endif
                            <div class="loginFormSocial_body">
                                <div class="loginFormSocial_body_item">
                                    <div id="g_id_onload" 
                                        data-_token="{{ csrf_token() }}" 
                                        data-client_id="{{ env('GOOGLE_DRIVE_CLIENT_ID') }}" 
                                        data-context="signin"
                                        data-ux_mode="popup"
                                        data-login_uri="{{ env('APP_URL') }}/auth/google/callback" 
                                        data-auto_prompt="false"
                                        data-auto_select="true"
                                        data-itp_support="true">
                                    </div>

                                    <div class="g_id_signin"
                                        data-type="standard"
                                        data-shape="rectangular"
                                        data-theme="filled_white"
                                        data-text="signin_with"
                                        data-size="large"
                                        data-logo_alignment="left">
                                    </div>
                                </div>
                                <div class="loginFormSocial_body_item">
                                    @php
                                        if(!empty($language)&&$language=='en'){
                                            $buttonTitleLoginWithFB = 'Login with Facebook';
                                        }else {
                                            $buttonTitleLoginWithFB = 'Đăng nhập với Facebook';
                                        }
                                    @endphp
                                    <a class="facebookButtonLogin" href="{{ route('main.facebook.redirect') }}">
                                        <img src="{{ Storage::url('images/svg/logo-facebook-fff.png') }}" alt="{{ $buttonTitleLoginWithFB }}" title="{{ $buttonTitleLoginWithFB }}" />
                                        <div>{{ $buttonTitleLoginWithFB }}</div>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                
            </div>
            </form>
        </div>
    </div>
    
</div>
{{-- @push('scriptCustom') --}}
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script type="text/javascript">        
        function toggleModalCustomerLoginForm(idElement){
            const element   = $('#'+idElement);
            const displayE  = element.css('display');
            if(displayE=='none'){
                /* hiển thị */
                element.css('display', 'flex');
                $('body').css('overflow', 'hidden');
                $('#js_openCloseModal_blur').addClass('blurBackground');
            }else {
                element.css('display', 'none');
                $('body').css('overflow', 'unset');
                $('#js_openCloseModal_blur').removeClass('blurBackground');
            }
        }
    </script>
    <script type="text/javascript">
        /* submit form */
        function submitFormLogin(idForm){
            const error     = validateFormLogin(idForm);
            if(error.length==0){
                /* tải loading */ 
                // loadLoading(idForm);
                /* lấy dữ liệu truyền đi */
                var data    = $('#'+idForm).serializeArray();
                $.ajax({
                    url         : '{{ route("admin.loginCustomer") }}',
                    type        : 'post',
                    dataType    : 'json',
                    data        : {
                        '_token'    : '{{ csrf_token() }}',
                        data        : data
                    },
                    success     : function(response){
                        if(response.flag==true){
                            window.location.href = '';
                        }else {
                            $('#noticeLogin').html(response.message);
                        }
                    }
                });
            }else {
                $.each(error, function(index, value){
                    const input = $('#'+idForm).find('[name='+value.name+']');
                    input.attr('placeholder', value.notice).css('border', '1px solid red');
                });
            }
        }
        /* validate form */
        function validateFormLogin(idForm){
            let error       = [];
            /* input required không được bỏ trống */
            $('#'+idForm).find('input[required]').each(function(){
                /* đưa vào mảng */
                if($(this).val()==''){
                    const errorItem = [];
                    errorItem['name']       = $(this).attr('name');
                    errorItem['notice']     = 'Không được để trống trường này';
                    error.push(errorItem);
                }
            });
            return error;
        }
    </script>
{{-- @endpush --}}