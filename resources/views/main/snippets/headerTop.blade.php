<div class="headerTop">
   <div class="container">
      <div class="headerTop_item">
         <div class="headerTop_item_hotline">
            <i class="fa-solid fa-phone"></i>hotline<span>{{ config('company.hotline') }}</span>
         </div>
      </div>
      <div class="headerTop_item">
         <div class="headerTop_item_list">
            {{-- <a href="#" class="headerTop_item_list_item">
               Tư vấn khách hàng
            </a> --}}
            <a href="/lien-he-hitour" title="Liên hệ {{ config('company.sortname') }}" class="headerTop_item_list_item">
               Liên hệ
            </a>
            {{-- <a href="/admin" title="Đăng nhập" class="headerTop_item_list_item">
               Đăng nhập
            </a> --}}
            <div id="js_checkLoginAndSetShow_button" class="headerTop_item_list_item js_toggleModalLogin"><div class="loginBox" onclick="toggleModalCustomerLoginForm('modalLoginFormCustomerBox');">
               <img src="/storage/images/svg/sign-in-alt.svg" alt="đăng nhập " title="đăng nhập" />
               <div class="maxLine_1">Đăng nhập</div>
               </div>
            </div>
         </div>
         <div class="headerTop_item_language">
            <div class="headerTop_item_language_item vi"></div>
            <div class="headerTop_item_language_item en"></div>
         </div>
      </div>
   </div>
</div>