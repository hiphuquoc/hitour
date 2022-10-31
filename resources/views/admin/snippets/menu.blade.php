<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow expanded" data-scroll-to-active="true" style="touch-action: none; user-select: none; -webkit-user-drag: none; -webkit-tap-highlight-color: rgba(0, 0, 0, 0);">
   <div class="navbar-header expanded">
      <ul class="nav navbar-nav flex-row">
         <li class="nav-item me-auto">
            <a class="navbar-brand" href="#">
               <span class="brand-logo">
                  <svg viewBox="0 0 139 95" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="24">
                     <defs>
                        <linearGradient id="linearGradient-1" x1="100%" y1="10.5120544%" x2="50%" y2="89.4879456%">
                           <stop stop-color="#000000" offset="0%"></stop>
                           <stop stop-color="#FFFFFF" offset="100%"></stop>
                        </linearGradient>
                        <linearGradient id="linearGradient-2" x1="64.0437835%" y1="46.3276743%" x2="37.373316%" y2="100%">
                           <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                           <stop stop-color="#FFFFFF" offset="100%"></stop>
                        </linearGradient>
                     </defs>
                     <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <g id="Artboard" transform="translate(-400.000000, -178.000000)">
                           <g id="Group" transform="translate(400.000000, 178.000000)">
                              <path class="text-primary" id="Path" d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z" style="fill:currentColor"></path>
                              <path id="Path1" d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z" fill="url(#linearGradient-1)" opacity="0.2"></path>
                              <polygon id="Path-2" fill="#000000" opacity="0.049999997" points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"></polygon>
                              <polygon id="Path-21" fill="#000000" opacity="0.099999994" points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"></polygon>
                              <polygon id="Path-3" fill="url(#linearGradient-2)" opacity="0.099999994" points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"></polygon>
                           </g>
                        </g>
                     </g>
                  </svg>
               </span>
               <h2 class="brand-text">Hitour</h2>
            </a>
         </li>
         <li class="nav-item nav-toggle">
            <a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse">
               <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x d-block d-xl-none text-primary toggle-icon font-medium-4">
                  <line x1="18" y1="6" x2="6" y2="18"></line>
                  <line x1="6" y1="6" x2="18" y2="18"></line>
               </svg>
               <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-disc d-none d-xl-block collapse-toggle-icon primary font-medium-4">
                  <circle cx="12" cy="12" r="10"></circle>
                  <circle cx="12" cy="12" r="3"></circle>
               </svg>
            </a>
         </li>
      </ul>
   </div>
   <div class="shadow-bottom"></div>
   <div class="main-menu-content ps ps--active-y customScrollBar-y">
      <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            @php
               $dataMenu            = config('menu.left-menu-admin');
               $routeCurrent        = request()->route()->getName();
               foreach($dataMenu as $menu){
                  $link             = !empty($menu['route']) ? route($menu['route']) : '#';
                  $flagSub          = !empty($child['child']) ? 'has-sub' : null;
                  $menuChild        = null;
                  $active           = !empty($menu['route'])&&$menu['route']==$routeCurrent ? 'active' : null;
                  if(!empty($menu['child'])){
                     $menuChild     = '<ul class="menu-content">';
                     foreach($menu['child'] as $child){
                        $active1    = !empty($child['route'])&&$child['route']==$routeCurrent ? 'active' : null;
                        $linkChild  = !empty($child['route']) ? route($child['route']) : '#';
                        $flagSub2   = !empty($child['child']) ? 'has-sub' : null;
                        $menuChild  .= '<li class="'.$flagSub2.' '.$active1.'">';
                        $menuChild  .= '  <a class="d-flex align-items-center" href="'.$linkChild.'">
                                             '.$child['icon'].'
                                             <span class="menu-title text-truncate">'.$child['name'].'</span>
                                          </a>';
                        if(!empty($child['child'])){
                           $menuChild     .= '<ul class="menu-content">';
                           foreach($child['child'] as $child2){
                              $linkChild2 = !empty($child2['route']) ? route($child2['route']) : '#';
                              $active2    = !empty($child2['route'])&&$child2['route']==$routeCurrent ? 'active' : null;
                              $menuChild .= '<li class="'.$active2.'">
                                                <a class="d-flex align-items-center" href="'.$linkChild2.'">
                                                   <span class="menu-item text-truncate" data-i18n="Basic">'.$child2['name'].'</span>
                                                </a>
                                             </li>';
                           }
                           $menuChild     .= '</ul>';
                        }
                        $menuChild  .= '</li>';
                     }
                     $menuChild     .= '</ul>';
                  }
                  echo '<li class="nav-item '.$flagSub.' '.$active.'">
                           <a href="'.$link.'" class="d-flex align-items-center">
                              '.$menu['icon'].'
                              <span class="menu-title text-truncate">'.$menu['name'].'</span>
                           </a>
                           '.$menuChild.'
                     </li>';
               }
            @endphp
      </ul>
   </div>
</div>
 <!-- END: Main Menu-->