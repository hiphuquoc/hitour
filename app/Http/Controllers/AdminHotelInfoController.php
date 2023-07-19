<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\Upload;
use Intervention\Image\ImageManagerStatic;
use App\Http\Controllers\AdminSliderController;
use App\Http\Controllers\AdminGalleryController;
use App\Models\HotelLocation;
use App\Models\HotelContent;
use App\Models\QuestionAnswer;
use App\Models\Hotel;
use App\Models\RelationHotelStaff;
use App\Models\Staff;
use App\Models\HotelImage;
use App\Models\Seo;
use Illuminate\Support\Facades\Cache;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\HotelRequest;
use App\Jobs\CheckSeo;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class AdminHotelInfoController extends Controller {

    private $arrayData;
    private $count;

    public function __construct(BuildInsertUpdateModel $BuildInsertUpdateModel){
        $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
    }

    public function list(Request $request){
        $params                         = [];
        /* Search theo tên */
        if(!empty($request->get('search_name'))) $params['search_name'] = $request->get('search_name');
        /* Search theo vùng miền */
        if(!empty($request->get('search_location'))) $params['search_location'] = $request->get('search_location');
        /* Search theo đối tác */
        if(!empty($request->get('search_partner'))) $params['search_partner'] = $request->get('search_partner');
        /* Search theo nhân viên */
        if(!empty($request->get('search_staff'))) $params['search_staff'] = $request->get('search_staff');
        /* paginate */
        $viewPerPage        = Cookie::get('viewHotelInfo') ?? 50;
        $params['paginate'] = $viewPerPage;
        /* lấy dữ liệu */
        $list                           = Hotel::getList($params);
        /* khu vực Combo */
        $hotelLocations                 = HotelLocation::all();
        /* nhân viên */
        $staffs                         = Staff::all();
        return view('admin.hotel.list', compact('list', 'params', 'viewPerPage', 'hotelLocations', 'staffs'));
    }

    public function view(Request $request){
        $hotelLocations     = HotelLocation::all();
        $staffs             = Staff::all();
        $parents            = HotelLocation::select('*')
                                ->with('seo')
                                ->get();
        $message            = $request->get('message') ?? null;
        $id                 = $request->get('id') ?? 0;
        /* nhận item nếu được redirect từ auto download */
        $item               = Cache::get('item_download');
        Cache::forget('item_download');
        if(empty($item)){
            $item           = Hotel::select('*')
                                ->where('id', $request->get('id'))
                                ->with(['files' => function($query){
                                    $query->where('relation_table', 'hotel_info');
                                }])
                                ->with('seo', 'contents', 'questions')
                                ->first();
        }
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.hotel.view', compact('item', 'type', 'hotelLocations', 'staffs', 'parents', 'message'));
    }

    public function create(HotelRequest $request){
        try {
            DB::beginTransaction();
            /* upload image */
            $dataPath           = [];
            $imageName          = !empty($request->get('slug')) ? $request->get('slug') : time();
            if($request->hasFile('image')) {
                $dataPath       = Upload::uploadThumnail($request->file('image'), $imageName);
            }
            /* insert page */
            $insertPage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'hotel_info', $dataPath);
            $pageId             = Seo::insertItem($insertPage);
            /* insert hotel_info */
            $insertHotelInfo    = $this->BuildInsertUpdateModel->buildArrayTableHotelInfo($request->all(), $pageId);
            $idHotel            = Hotel::insertItem($insertHotelInfo);
            /* lưu ảnh vào cơ sở dữ liệu */
            if(!empty($request->get('images'))){
                self::saveImagesHotelInfo($imageName, $idHotel, $request->get('images'), 'hotel_info');
            }
            /* insert hotel_content */
            if(!empty($request->get('contents'))){
                foreach($request->get('contents') as $content){
                    if(!empty($content['name'])&&!empty($content['content'])){
                        HotelContent::insertItem([
                            'hotel_info_id' => $idHotel,
                            'name'          => $content['name'],
                            'content'       => $content['content']
                        ]);
                    }
                }
            }
            /* insert slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idHotel)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotel,
                    'relation_table'    => 'hotel_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* insert gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idHotel)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotel,
                    'relation_table'    => 'hotel_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* insert relation_hotel_staff */
            if(!empty($idHotel)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'hotel_info_id'     => $idHotel,
                        'staff_info_id'     => $staff
                    ];
                    RelationHotelStaff::insertItem($params);
                }
            }
            /* insert câu hỏi thường gặp */
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'hotel_info',
                            'reference_id'      => $idHotel
                        ]);
                    }
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Dã tạo Hotel mới'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message        = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($request->get('seo_id'));
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.hotel.view', ['id' => $idHotel]);
    }

    public function update(HotelRequest $request){
        try {
            DB::beginTransaction();
            $idHotel            = $request->get('hotel_info_id') ?? 0;
            /* upload image */
            $dataPath           = [];
            $imageName          = !empty($request->get('slug')) ? $request->get('slug') : time();
            if($request->hasFile('image')) {
                $dataPath       = Upload::uploadThumnail($request->file('image'), $imageName);
            };
            /* update page */
            $updatePage         = $this->BuildInsertUpdateModel->buildArrayTableSeo($request->all(), 'hotel_info', $dataPath);
            Seo::updateItem($request->get('seo_id'), $updatePage);
            /* update hotel_info */
            $updateHotelInfo     = $this->BuildInsertUpdateModel->buildArrayTableHotelInfo($request->all(), $request->get('seo_id'));
            Hotel::updateItem($idHotel, $updateHotelInfo);
            /* lưu ảnh vào cơ sở dữ liệu */
            if(!empty($request->get('images'))){
                self::saveImagesHotelInfo($imageName, $idHotel, $request->get('images'), 'hotel_info');
            }
            /* update hotel_content */
            HotelContent::select('*')
                            ->where('hotel_info_id', $idHotel)
                            ->delete();
            if(!empty($request->get('contents'))){
                foreach($request->get('contents') as $content){
                    if(!empty($content['name'])&&!empty($content['content'])){
                        HotelContent::insertItem([
                            'hotel_info_id' => $idHotel,
                            'name'          => $content['name'],
                            'content'       => $content['content']
                        ]);
                    }
                }
            }
            /* update slider và lưu CSDL */
            if($request->hasFile('slider')&&!empty($idHotel)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotel,
                    'relation_table'    => 'hotel_info',
                    'name'              => $name
                ];
                AdminSliderController::uploadSlider($request->file('slider'), $params);
            }
            /* update gallery và lưu CSDL */
            if($request->hasFile('gallery')&&!empty($idHotel)){
                $name           = !empty($request->get('slug')) ? $request->get('slug') : time();
                $params         = [
                    'attachment_id'     => $idHotel,
                    'relation_table'    => 'hotel_info',
                    'name'              => $name
                ];
                AdminGalleryController::uploadGallery($request->file('gallery'), $params);
            }
            /* update relation_hotel_staff */
            RelationHotelStaff::select('*')
                ->where('hotel_info_id', $idHotel)
                ->delete();
            if(!empty($idHotel)&&!empty($request->get('staff'))){
                foreach($request->get('staff') as $staff){
                    $params     = [
                        'hotel_info_id'     => $idHotel,
                        'staff_info_id'     => $staff
                    ];
                    RelationHotelStaff::insertItem($params);
                }
            }
            /* update câu hỏi thường gặp */
            QuestionAnswer::select('*')
                ->where('relation_table', 'hotel_info')
                ->where('reference_id', $idHotel)
                ->delete();
            if(!empty($request->get('question_answer'))){
                foreach($request->get('question_answer') as $itemQues){
                    if(!empty($itemQues['question'])&&!empty($itemQues['answer'])){
                        QuestionAnswer::insertItem([
                            'question'          => $itemQues['question'],
                            'answer'            => $itemQues['answer'],
                            'relation_table'    => 'hotel_info',
                            'reference_id'      => $idHotel
                        ]);
                    }
                }
            }
            DB::commit();
            /* Message */
            $message        = [
                'type'      => 'success',
                'message'   => '<strong>Thành công!</strong> Các thay đổi đã được lưu'
            ];
        } catch (\Exception $exception){
            DB::rollBack();
            /* Message */
            $message        = [
                'type'      => 'danger',
                'message'   => '<strong>Thất bại!</strong> Có lỗi xảy ra, vui lòng thử lại'
            ];
        }
        /* ===== START:: check_seo_info */
        CheckSeo::dispatch($request->get('seo_id'));
        /* ===== END:: check_seo_info */
        $request->session()->put('message', $message);
        return redirect()->route('admin.hotel.view', ['id' => $idHotel]);
    }

    public function delete(Request $request){
        if(!empty($request->get('id'))){
            try {
                DB::beginTransaction();
                $idHotel     = $request->get('id');
                /* lấy hotel_option (with hotel_price) */
                $infoHotel   = Hotel::select('*')
                                    ->where('id', $idHotel)
                                    ->with(['files' => function($query){
                                        $query->where('relation_table', 'hotel_info');
                                    }])
                                    ->with('seo', 'images', 'rooms', 'staffs', 'contacts', 'contents', 'questions')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục upload */
                if(!empty($infoHotel->seo->image)&&file_exists(public_path($infoHotel->seo->image))) @unlink(public_path($infoHotel->seo->image));
                if(!empty($infoHotel->seo->image_small)&&file_exists(public_path($infoHotel->seo->image_small))) @unlink(public_path($infoHotel->seo->image_small));
                /* xóa hotel_content */
                $infoHotel->contents()->delete();
                /* xóa ảnh phòng */
                foreach($infoHotel->images as $image){
                    if(!empty($image->image)&&file_exists(Storage::path($image->image))) @unlink(Storage::path($image->image));
                    if(!empty($image->image_small)&&file_exists(Storage::path($image->image_small))) @unlink(Storage::path($image->image_small));
                }
                /* xóa hotel_contact */
                $infoHotel->contacts()->delete();
                /* xóa relation staff */
                $infoHotel->staffs()->delete();
                /* xóa phòng và tất cả ảnh + thông tin phòng */
                foreach($infoHotel->rooms as $room) AdminHotelRoomController::deleteById($room->id);
                /* xóa files */
                foreach($infoHotel->files as $file){
                    if(file_exists(Storage::path($file->file_name))) @unlink(Storage::path($file->file_name));
                }
                $infoHotel->files()->delete();
                /* xóa câu hỏi thường gặp */
                $infoHotel->questions()->delete();
                /* xóa seo */
                $infoHotel->seo()->delete();
                /* xóa hotel_info */
                $infoHotel->delete();
                DB::commit();
                return true;
            } catch (\Exception $exception){
                DB::rollBack();
                return false;
            }
        }
    }

    public function removeImageHotelInfo(Request $request){
        $flag       = false;
        if(!empty($request->get('hotel_image_id'))){
            $infoHotelImage = HotelImage::select('*')
                                ->where('id', $request->get('hotel_image_id'))
                                ->first();
            /* xóa trong storage */
            if(!empty($infoHotelImage->image)&&file_exists(Storage::path($infoHotelImage->image))) @unlink(Storage::path($infoHotelImage->image));
            if(!empty($infoHotelImage->image_small)&&file_exists(Storage::path($infoHotelImage->image_small))) @unlink(Storage::path($infoHotelImage->image_small));
            /* xóa trong database */
            $flag   = $infoHotelImage->delete();
        }
        echo $flag;
    }

    public function downloadHotelInfo(Request $request){
        try {
            if(!empty($request->get('url_crawler'))){
                // Tạo đối tượng Client của Goutte
                $client         = new Client();
                // Gửi yêu cầu GET đến URL cần lấy dữ liệu
                $url            = $request->get('url_crawler');
                $crawlerContent = $client->request('GET', $url);

                /* lấy url_crawler */
                $this->arrayData['url_crawler']         = $request->get('url_crawler');
                /* lấy tên khách sạn */
                $this->arrayData['name']                = trim($crawlerContent->filter('h1')->text());
                /* lấy giới thiệu khách sạn */
                $crawlerContent->filter('#hotel_description > div > div > div')->each(function($node){
                    $this->arrayData['description'][]   = $node->html();
                });
                if(!empty($this->arrayData['description'])){
                    $this->arrayData['description']     = implode('', $this->arrayData['description']);
                }else {
                    $this->arrayData['description']     = null;
                }
                /* lấy tên khách sạn (SEO) */
                $this->arrayData['seo']['seo_title']    = trim($crawlerContent->filter('head title')->text());
                /* lấy mô tả khách sạn (SEO) */
                $crawlerContent->filter('head meta[name=description]')->each(function($node){
                    $this->arrayData['seo']['seo_description'] = $node->attr('content');
                });
                /* tự động slug theo tên */
                $this->arrayData['seo']['slug']         = \App\Helpers\Charactor::convertStrToUrl($this->arrayData['name']);

                /* lấy câu hỏi thường gặp */
                $this->count            = 0;
                $crawlerContent->filter('[class^="HotelFAQ_content"] > div h3')->each(function($node){
                    $this->arrayData['questions'][$this->count]['question']  = trim($node->text());
                    $this->count    += 1;
                });
                $this->count            = 0;
                $crawlerContent->filter('[class^="HotelFAQ_content"] > div > div')->each(function($node){
                    $tmp                = trim($node->html());
                    $tmp                = str_replace(['<p></p>', '<span></span>', '<div></div>', '<li></li>'], '', $tmp);
                    $this->arrayData['questions'][$this->count]['answer']    = $tmp;
                    $this->count    += 1;
                });

                /* lấy chính sách khách sạn */
                $this->arrayData['contents'][0]['name']     = trim($crawlerContent->filter('#hotel_policy h2')->text());
                $this->arrayData['contents'][0]['content']  = '<div>'.trim($crawlerContent->filter('#hotel_policy')->html()).'</div>';

                /* random star * rating */
                $this->arrayData['seo']['rating_aggregate_star']    = '4.'.rand(5,8);
                $this->arrayData['seo']['rating_aggregate_count']   = rand(100,300);
                /* lấy dữ liệu trong db truyển đi kèm */
                $hotelLocations     = HotelLocation::all();
                $staffs             = Staff::all();
                $parents            = HotelLocation::select('*')
                                        ->with('seo')
                                        ->get();
                /* type */
                $type               = !empty($item) ? 'edit' : 'create';
                $type               = $request->get('type') ?? $type;
                // $item               = $this->arrayData;
                $item               = self::convertToCollectionRecursive($this->arrayData);
                $item->id           = null;
                $item->seo          = $item['seo'];
                $item->seo->id      = null;
                $item->seo->link_canonical = null;
                $item->company_name = null;
                $item->address      = null;
                $item->company_code = null;
                $item->website      = null;
                $item->hotline      = null;
                $item->email        = null;
                $item->contents     = $item['contents'];
                $item->questions    = $item['questions'];
                Cache::put('item_download', $item, 60); // Lưu trữ trong 60 phút
                return redirect()->route('admin.hotel.view', ['type' => 'create']);
                // return view('admin.hotel.view', compact('item', 'type', 'hotelLocations', 'staffs', 'parents'));
            }} catch (\Exception $exception){
                return redirect()->route('admin.hotel.view');
            }
    }

    public function downloadImageHotelInfo(Request $request){
        if(!empty($request->get('content_image'))){
            $data               = $request->get('content_image');
            $crawlerContent     = new Crawler($data);
    
            /* lấy ảnh khách sạn */
            $crawlerContent->filter('img')->each(function($node){
                $filteredUrl = preg_replace('/^http.+(http.*)/', '$1', $node->attr('src'));
                $filteredUrl = preg_replace('/\?[^\/]+/', '', $filteredUrl);
                
                $this->arrayData['images'][] = $filteredUrl;
            });

            /* duyệt qua để lọc bỏ đường dẫn không phải ảnh */
            $allowedExtensions  = ['png', 'jpg', 'jpeg'];
            $imagesReal         = [];
            foreach($this->arrayData['images'] as $image){
                $extension = pathinfo($image, PATHINFO_EXTENSION);
                if (in_array($extension, $allowedExtensions)) {
                    $imagesReal[] = $image;
                }
            }
        }
        return json_encode($imagesReal);
    }

    public static function saveImagesHotelInfo($imageName, $idHotelRoom, $imageUrls, $table = 'hotel_info'){
        $i      = 0;
        foreach ($imageUrls as $imageUrl) {
            /*  folder upload */
            $folderUpload   = config('admin.images.folderHotel');
            /* image upload */
            $extension      = config('admin.images.extension');
            /* upload ảnh normal */
            $name           = $imageName.'-'.$i.'-'.time();
            $filenameNormal = $folderUpload.$name.'.'.$extension;
            ImageManagerStatic::make($imageUrl)
                ->encode($extension, config('admin.images.quality'))
                ->save(Storage::path($filenameNormal));
            /* upload ảnh small */
            // /* lấy width và height của ảnh truyền vào để tính percenter resize */
            // $imageTmp           = ImageManagerStatic::make($imageUrl);
            // $percentPixel       = $imageTmp->width()/$imageTmp->height();
            // $widthImageSmall    = config('admin.images.smallResize_width');
            // $heightImageSmall   = $widthImageSmall/$percentPixel;
            // $filenameSmall      = $folderUpload.$name.'-small.'.$extension;
            // ImageManagerStatic::make($imageUrl)
            //     ->encode($extension, config('admin.images.quality'))
            //     ->resize(config('admin.images.smallResize_width'), $heightImageSmall)
            //     ->save(Storage::path($filenameSmall));
            HotelImage::insertItem([
                'reference_type'    => $table,
                'reference_id'      => $idHotelRoom,
                'image'             => $filenameNormal,
                'image_small'       => null
            ]);
            ++$i;
        }
    }

    public static function convertToCollectionRecursive($array){
        $result = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result[$key] = self::convertToCollectionRecursive($value);
            } else {
                $result[$key] = $value;
            }
        }

        return collect($result);
    }
}

