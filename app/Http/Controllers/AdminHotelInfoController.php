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
use App\Models\Seo;
use App\Models\Comment;
use Illuminate\Support\Facades\Cache;
use App\Services\BuildInsertUpdateModel;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\HotelRequest;
use App\Jobs\CheckSeo;
use App\Jobs\DownloadCommentHotelInfo;
use App\Jobs\DownloadHotelImageToCloudStorage;
use App\Models\HotelFacility;
use App\Models\RelationHotelInfoHotelFacility;
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
                                ->with('seo', 'facilities', 'contents', 'questions')
                                ->first();
        }
        $facilities         = HotelFacility::all();
        $content            = null;
        if(!empty($item->seo->slug)){
            $content        = Storage::get(config('admin.storage.contentHotel').$item->seo->slug.'.blade.php');
        }
        /* type */
        $type               = !empty($item) ? 'edit' : 'create';
        $type               = $request->get('type') ?? $type;
        return view('admin.hotel.view', compact('item', 'type', 'hotelLocations', 'staffs', 'parents', 'facilities', 'content', 'message'));
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
                self::saveImage($imageName, $idHotel, $request->get('images'), 'hotel_info');
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
            /* insert comments => tải từ tripadvisor */
            DownloadCommentHotelInfo::dispatch($idHotel, 0);
            /* insert comments => tử nhập */
            if(!empty($request->get('comments'))){
                foreach($request->get('comments') as $comment){
                    $insertComment = $this->BuildInsertUpdateModel->buildArrayTableCommentInfo($comment, $idHotel, 'hotel_info');
                    Comment::insertItem($insertComment);
                }
            }
            /* lưu content vào file */
            if(!empty($request->get('content'))) Storage::put(config('admin.storage.contentHotel').$request->get('slug').'.blade.php', $request->get('content'));
            /* insert relation_hotel_info_hotel_facility */
            if(!empty($request->get('facilities'))){
                foreach($request->get('facilities') as $idFacility){
                    RelationHotelInfoHotelFacility::insertItem([
                        'hotel_info_id'     => $idHotel,
                        'hotel_facility_id' => $idFacility
                    ]);
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
                self::saveImage($imageName, $idHotel, $request->get('images'), 'hotel_info');
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
            /* xóa và insert comments => xử lý riêng ở một controller khác (giảm tải) */
            /* lưu content vào file */
            if(!empty($request->get('content'))){
                Storage::put(config('admin.storage.contentHotel').$request->get('slug').'.blade.php', $request->get('content'));
            }else {
                Storage::delete(config('admin.storage.contentHotel').$request->get('slug').'.blade.php');
            }
            /* insert relation_hotel_info_hotel_facility */
            RelationHotelInfoHotelFacility::select('*')
                ->where('hotel_info_id', $idHotel)
                ->delete();
            if(!empty($request->get('facilities'))){
                foreach($request->get('facilities') as $idFacility){
                    RelationHotelInfoHotelFacility::insertItem([
                        'hotel_info_id'     => $idHotel,
                        'hotel_facility_id' => $idFacility
                    ]);
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
                $idHotel        = $request->get('id');
                /* lấy hotel_option (with hotel_price) */
                $infoHotel      = Hotel::select('*')
                                    ->where('id', $idHotel)
                                    ->with(['files' => function($query){
                                        $query->where('relation_table', 'hotel_info');
                                    }])
                                    ->with('seo', 'images', 'rooms', 'staffs', 'contacts', 'contents', 'comments', 'questions')
                                    ->first();
                /* xóa ảnh đại diện trong thư mục upload */
                if(!empty($infoHotel->seo->image)&&file_exists(public_path($infoHotel->seo->image))) @unlink(public_path($infoHotel->seo->image));
                if(!empty($infoHotel->seo->image_small)&&file_exists(public_path($infoHotel->seo->image_small))) @unlink(public_path($infoHotel->seo->image_small));
                /* xóa hotel_content */
                $infoHotel->contents()->delete();
                /* xóa ảnh phòng */
                foreach($infoHotel->images as $image) Storage::disk('gcs')->delete($image->image);
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
                /* xóa content */
                Storage::delete(config('admin.storage.contentHotel').$infoHotel->seo->slug.'.blade.php');
                /* xóa comment */
                $infoHotel->comments()->delete();
                /* xóa relation_hotel_info_hotel_facility */
                $infoHotel->facilities()->delete();
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

    public function removeAllImageHotelInfo(Request $request){
        $flag               = false;
        if(!empty($request->get('hotel_info_id'))){
            $hotelInfo    = Hotel::select('*')
                                ->where('id', $request->get('hotel_info_id'))
                                ->with('images')
                                ->first();
            /* xóa trong storage */
            foreach($hotelInfo->images as $image){
                Storage::disk('gcs')->delete($image->image);
            }
            /* xóa trong database */
            $flag           = $hotelInfo->images()->delete();
        }
        echo true;
    }

    public function downloadHotelInfo(Request $request){
        try {
            /* ============= Lấy dữ liệu của Mytour */
            if(!empty($request->get('url_crawler_mytour'))) $this->downloadHotelInfo_mytour($request->get('url_crawler_mytour'));
            /* ============= Lấy dữ liệu của Tripadvisor */
            if(!empty($request->get('url_crawler_tripadvisor'))) $this->downloadHotelInfo_tripadvisor($request->get('url_crawler_tripadvisor'));
            /* ============= Lấy dữ liệu của Traveloka */
            if(!empty($request->get('url_crawler_traveloka'))) $this->downloadHotelInfo_traveloka($request->get('url_crawler_traveloka'));

            $type               = !empty($item) ? 'edit' : 'create';
            $type               = $request->get('type') ?? $type;
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
            $item->url_crawler_mytour       = $request->get('url_crawler_mytour') ?? null;
            $item->url_crawler_traveloka    = $request->get('url_crawler_traveloka') ?? null;
            $item->url_crawler_tripadvisor  = $request->get('url_crawler_tripadvisor') ?? null;
            /* (xử lý facility lấy từ tripadvisor) tiến hành lọc facility nào chưa có trong bảng CSDL thì tạo ra */
            $item->facilities   = self::insertOrGetHotelFacility($item['tmpFacilities']);
            /* trường hợp không lấy được facilities từ tripadvisor thì sẽ xử lý facilities lấy từ traveloka */
            if(!empty($item->facilities)&&$item->facilities->isNotEmpty()){
                /* không làm gì cả */
            }else {
                $i                  = 0;
                $arrayFacilities    = [];
                foreach($this->arrayData['facilitiesTraveloka_item'] as $list){
                    foreach($list as $child){
                        $arrayFacilities[] = [
                            'name'          => $child,
                            'category_name' => $this->arrayData['facilitiesTraveloka_h3'][$i],
                            'icon'          => '<svg viewBox="0 0 24 24" width="1em" height="1em" class="d Vb UmNoP"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.53 7.53L9 18.06l-5.53-5.53 1.06-1.06L9 15.94l9.47-9.47 1.06 1.06z"></path></svg>',
                            'type'          => null,
                            'highlight'     => 0
                        ];
                    }
                    ++$i;
                }
                $item->facilities   = self::insertOrGetHotelFacility($arrayFacilities);
            }
            // Lưu trữ trong 60 phút
            Cache::put('item_download', $item, 3600); 
            return redirect()->route('admin.hotel.view', ['type' => 'create']);
        } catch (\Exception $exception){
            return redirect()->route('admin.hotel.view', ['type' => 'create']);
        }
    }

    private static function insertOrGetHotelFacility($arrayFacilities = null){
        $result = new \Illuminate\Database\Eloquent\Collection;
        if(!empty($arrayFacilities)){
            $allFacilities      = HotelFacility::all();
            $tmp                = [];
            foreach($arrayFacilities as $f){
                $flag           = false;
                foreach($allFacilities as $facility){
                    /* facility này đã có trong cơ sở dữ liệu => lấy id đưa vào mảng */
                    if($f['name']==$facility->name&&$f['type']==$facility->type){
                        $flag       =  true;
                        $tmp[]      = $facility->id;
                        break;
                    }
                }
                /* flag = false => facility này chưa có trong cơ sở dữ liệu => insert vào sau đó lấy id đưa vào mảng */
                if($flag==false){
                    $idFacility = HotelFacility::insertItem([
                        'name'          => $f['name'],
                        'category_name' => $f['category_name'] ?? null,
                        'icon'          => $f['icon'] ?? null,
                        'type'          => $f['type'] ?? null,
                        'highlight'     => $f['highlight'] ?? 0

                    ]);
                    $tmp[]      = $idFacility;
                }
            }
            $result     = HotelFacility::select('*')
                                    ->whereIn('id', $tmp)
                                    ->get();
        }
        return $result;
    }

    public function downloadHotelInfo_mytour($url){
        $flag               = false;
        if(!empty($url)){
            // Tạo đối tượng Client của Goutte
            $client         = new Client();
            // Gửi yêu cầu GET đến URL cần lấy dữ liệu
            $crawlerContent = $client->request('GET', $url);
            /* lấy url_crawler */
            $this->arrayData['url_crawler_mytour']  = $url;
            /* lấy tên khách sạn */
            $this->arrayData['name']                = trim($crawlerContent->filter('h1')->text());
            /* lấy giới thiệu khách sạn */
            $crawlerContent->filter('#hotel_description > div > div > div')->each(function($node){
                $this->arrayData['content'][]   = $node->html();
            });
            if(!empty($this->arrayData['content'])){
                $this->arrayData['content']     = implode('', $this->arrayData['content']);
            }else {
                $this->arrayData['content']     = null;
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

            $flag = true;
        }
        return $flag;
    }

    public function downloadHotelInfo_tripadvisor($url){
        $flag               = false;
        if(!empty($url)){
            // Tạo đối tượng Client của Goutte
            $client         = new Client();
            // Gửi yêu cầu GET đến URL cần lấy dữ liệu
            $crawlerContent = $client->request('GET', $url);
            $this->arrayData['url_crawler_tripadvisor'] = $url;
            /* địa chỉ khách sạn */
            $this->arrayData['address']     = $crawlerContent->filter('.gZwVG')->text();
            /* ===== tiện nghi khách sạn */
            $crawlerContent->filter('.MXlSZ .ssr-init-26f')->each(function($node){
                $this->arrayData['tmp'][]   = $node->attr('data-ssrev-handlers');
            });
            /* lấy tất cả thông tin */
            $dataFacility                   = [];
            $this->arrayData['description'] = null;
            foreach($this->arrayData['tmp'] as $tmp){
                $tmp                        = json_decode($tmp, true);
                foreach($tmp['load'] as $t){
                    if(!empty($t['amenities'])) $dataFacility = $t['amenities'];
                    if(!empty($t['locationDescription'])) $this->arrayData['description'] = $t['locationDescription'];
                }
            }
            /* build lại mảng facilities */
            $this->arrayData['tmpFacilities']  = [];
            if(!empty($dataFacility['highlightedAmenities']['roomFeatures'])) {
                /* duyệt qua mảng lấy tiện nghi nổi bật -> loại tiện nghi trong phòng */
                $i      = 0;
                foreach($dataFacility['highlightedAmenities']['roomFeatures'] as $facility){
                    if(!empty($facility['amenityNameLocalized'])) $this->arrayData['tmpFacilities'][$i]['name']    = $facility['amenityNameLocalized'];
                    if(!empty($facility['amenityCategoryName'])) $this->arrayData['tmpFacilities'][$i]['category_name']     = $facility['amenityCategoryName'];
                    $this->arrayData['tmpFacilities'][$i]['type']      = 'hotel_room_feature';
                    $this->arrayData['tmpFacilities'][$i]['highligh']  = 1;
                    ++$i;
                }
                /* duyệt qua mảng lấy tiện nghi thường -> loại tiện nghi trong phòng */
                foreach($dataFacility['nonHighlightedAmenities']['roomFeatures'] as $facility){
                    if(!empty($facility['amenityNameLocalized'])) $this->arrayData['tmpFacilities'][$i]['name']    = $facility['amenityNameLocalized'];
                    if(!empty($facility['amenityCategoryName'])) $this->arrayData['tmpFacilities'][$i]['category_name']     = $facility['amenityCategoryName'];
                    $this->arrayData['tmpFacilities'][$i]['type']      = 'hotel_room_feature';
                    $this->arrayData['tmpFacilities'][$i]['highligh']  = 0;
                    ++$i;
                }
                /* duyệt qua mảng lấy tiện nghi nổi bật -> loại loại phòng */
                foreach($dataFacility['highlightedAmenities']['roomTypes'] as $facility){
                    if(!empty($facility['amenityNameLocalized'])) $this->arrayData['tmpFacilities'][$i]['name']    = $facility['amenityNameLocalized'];
                    if(!empty($facility['amenityCategoryName'])) $this->arrayData['tmpFacilities'][$i]['category_name']     = $facility['amenityCategoryName'];
                    $this->arrayData['tmpFacilities'][$i]['type']      = 'hotel_room_type';
                    $this->arrayData['tmpFacilities'][$i]['highligh']  = 1;
                    ++$i;
                }
                /* duyệt qua mảng lấy tiện nghi thường -> loại loại phòng */
                foreach($dataFacility['nonHighlightedAmenities']['roomTypes'] as $facility){
                    if(!empty($facility['amenityNameLocalized'])) $this->arrayData['tmpFacilities'][$i]['name']    = $facility['amenityNameLocalized'];
                    if(!empty($facility['amenityCategoryName'])) $this->arrayData['tmpFacilities'][$i]['category_name']     = $facility['amenityCategoryName'];
                    $this->arrayData['tmpFacilities'][$i]['type']      = 'hotel_room_type';
                    $this->arrayData['tmpFacilities'][$i]['highligh']  = 0;
                    ++$i;
                }
                /* duyệt qua mảng lấy tinh năng nổi bật -> loại tiện nghi chung khách sạn */
                foreach($dataFacility['highlightedAmenities']['propertyAmenities'] as $facility){
                    if(!empty($facility['amenityNameLocalized'])) $this->arrayData['tmpFacilities'][$i]['name']    = $facility['amenityNameLocalized'];
                    if(!empty($facility['amenityCategoryName'])) $this->arrayData['tmpFacilities'][$i]['category_name']     = $facility['amenityCategoryName'];
                    $this->arrayData['tmpFacilities'][$i]['type']      = 'hotel_info_feature';
                    $this->arrayData['tmpFacilities'][$i]['highligh']  = 1;
                    ++$i;
                }
                /* duyệt qua mảng lấy tinh năng nổi bật -> loại tiện nghi chung khách sạn */
                foreach($dataFacility['nonHighlightedAmenities']['propertyAmenities'] as $facility){
                    if(!empty($facility['amenityNameLocalized'])) $this->arrayData['tmpFacilities'][$i]['name']    = $facility['amenityNameLocalized'];
                    if(!empty($facility['amenityCategoryName'])) $this->arrayData['tmpFacilities'][$i]['category_name']     = $facility['amenityCategoryName'];
                    $this->arrayData['tmpFacilities'][$i]['type']      = 'hotel_info_feature';
                    $this->arrayData['tmpFacilities'][$i]['highligh']  = 0;
                    ++$i;
                }
            }
            /* lấy facilities có icon => để gộp vào mảng facilities chính */
            $this->count    = 0;
            $crawlerContent->filter('[data-test-target*=amenity_text]')->each(function($node){
                /* icon */
                $spanNode       = $node->filter('svg')->getNode(0);
                $spanDom        = $node->getNode(0)->ownerDocument->saveHTML($spanNode);
                $this->arrayData['tmpFacilitiesWithIcon'][$this->count]['icon']   = trim($spanDom);
                /* name */
                $this->arrayData['tmpFacilitiesWithIcon'][$this->count]['name']   = $node->text();
                $this->count += 1;
            });
            /* gộp 2 mảng facilities lại */
            for($i=0;$i<count($this->arrayData['tmpFacilities']);++$i){
                foreach($this->arrayData['tmpFacilitiesWithIcon'] as $icon){
                    if($icon['name']==$this->arrayData['tmpFacilities'][$i]['name']){
                        $this->arrayData['tmpFacilities'][$i]['icon'] = $icon['icon'];
                        break;
                    }
                }
            }
            /* đánh giá tổng quan */
            $crawlerContent->filter('.grdwI')->each(function($node){
                $this->arrayData['seo']['rating_aggregate_star']     = str_replace(',', '.', $node->filter('.uwJeR')->text());
                $this->arrayData['seo']['rating_aggregate_text']     = $node->filter('.kkzVG')->text();
                $this->arrayData['seo']['rating_aggregate_count']    = preg_replace("/[^0-9]/", "", $node->filter('.hkxYU')->text());
            });
            /* đánh giá chi tiết */
            $crawlerContent->filter('.MXlSZ .HXCfp')->each(function($node){
                $numberClass                                         = preg_replace("/[^0-9]/", '', $node->filter('span')->attr('class')); /* 40 /45 /50 */
                $this->arrayData['rating_detail'][]                  = $numberClass/10;
                /*  mảng gồm 4 phần tử:
                    0 là địa điểm
                    1 là sự sạch sẽ
                    2 là dịch vụ
                    3 là giá trị
                */
            });
            /* comment => tải bằng job khi tạo khách sạn mới */
            // $this->getComment_tripadvisor($url, 0, $this->count);
            $flag = true;
        }
        return $flag;
    }

    public function downloadHotelInfo_traveloka($url){
        $flag       = false;
        if(!empty($url)){
            // Tạo đối tượng Client của Goutte
            $client         = new Client();
            // Gửi yêu cầu GET đến URL cần lấy dữ liệu
            $crawlerContent = $client->request('GET', $url);
            $this->arrayData['url_crawler_traveloka'] = $url;
            /* ===== loại khách sạn */
            $crawlerContent->filter('.r-ml3lyg .r-1ssbvtb .r-1h0z5md')->each(function($node){
                $this->arrayData['type_name'][]     = $node->text();
            });
            $this->arrayData['type_name']           = trim($this->arrayData['type_name'][0]) ?? '';
            /* ===== sao khách sạn */
            $crawlerContent->filter('.r-ml3lyg .r-1ssbvtb img')->each(function($node){
                $this->arrayData['type_rating'][]   = $node->text();
            });
            $rating                                 = !empty($this->arrayData['type_rating']) ? count($this->arrayData['type_rating']) : 0;
            if($rating>0&&$rating<=5){
                $this->arrayData['type_rating']     = count($this->arrayData['type_rating']);
            }else {
                $this->arrayData['type_rating']     = 0;
            }
            /* ===== tiện nghi khách sạn (traveloka) ===== */
            $this->count                    = 0;
            $crawlerContent->filter('.r-1fdih9r .css-1dbjc4n ul')->each(function($node){
                $node->filter('li')->each(function($node2){
                    $this->arrayData['facilitiesTraveloka_item'][$this->count][] = $node2->text();
                    
                });
                $this->count    += 1;
            });
            $crawlerContent->filter('.r-1fdih9r .css-1dbjc4n h3')->each(function($node){
                $this->arrayData['facilitiesTraveloka_h3'][]    = $node->html();
            });
            $flag   = true;
        }
        return $flag;
    }

    // private function getComment_tripadvisor($url, $number, $count){
    //     try {
    //         /* Tạo đối tượng Client của Goutte */
    //         $client         = new Client();
    //         $everyTime      = 5; /* đây là số comment mặc định trên mỗi trang của tripadvisor */
    //         /* Gửi yêu cầu GET đến URL cần lấy dữ liệu */
    //         $url            = explode('Reviews', $url);
    //         $url            = implode('Reviews-or'.$number, $url);
    //         $crawlerContent = $client->request('GET', $url);
    //         /* lấy comment */
    //         $this->count    = $count;
    //         if($crawlerContent->filter('[data-test-target=reviews-tab] .YibKl')->count()>0){
    //             $crawlerContent->filter('[data-test-target=reviews-tab] .YibKl')->each(function($node){
    //                 /* số sao */
    //                 $number         = preg_replace("/[^0-9]/", '', $node->filter('[data-test-target=review-rating] > span')->attr('class'));
    //                 $this->arrayData['comments'][$this->count]['rating']        = $number/10;
    //                 /* người đánh giá + lúc đánh giá */
    //                 $tmp            = $node->filter('.cRVSd')->text();
    //                 $tmp            = explode('đã viết đánh giá vào', $tmp);
    //                 $authorName     = $tmp[0];
    //                 /* xử lý ngày tháng comment */
    //                 $chuoiNgayThang = $tmp[1];
    //                 $mangChuoi      = explode(" ", $chuoiNgayThang);
    //                 $thang          = 0;
    //                 $tungay         = array("thg", " ", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
    //                 $denngay        = array("", "", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
    //                 $mangChuoi[1]   = str_replace($tungay, $denngay, strtolower($mangChuoi[1]));
    //                 $thang          = (int)$mangChuoi[1];
    //                 $this->arrayData['comments'][$this->count]['created_at']    = sprintf("%d-%02d-%02d", (int)$mangChuoi[2], $thang, 01);

    //                 $this->arrayData['comments'][$this->count]['author_name']   = $authorName;
    //                 /* tiêu đề đánh giá */
    //                 $this->arrayData['comments'][$this->count]['title']         = $node->filter('.Qwuub')->text();
    //                 /* nội dung đánh giá */
    //                 $this->arrayData['comments'][$this->count]['comment']       = $node->filter('.fIrGe')->text();
    
    //                 $this->count    += 1;
    //             });
    //             $this->getComment_tripadvisor($url, ($number + $everyTime), $this->count);
    //         }else {
    //             return true;
    //         }
    //     }catch (\Exception $e) {
    //         return false;
    //     }
    // }

    public function loadFormDownloadImageHotelInfo(Request $request){
        $idHotel    = $request->get('hotel_info_id') ?? null;
        $result     = view('admin.hotel.formDownloadImage', compact('idHotel'))->render();
        echo $result;
    }

    public function downloadImageHotelInfo(Request $request){
        $imagesReal             = [];
        if(!empty($request->get('content_image'))&&!empty($request->get('hotel_info_id'))){
            $data               = $request->get('content_image');
            $crawlerContent     = new Crawler($data);
    
            /* lấy ảnh khách sạn */
            $crawlerContent->filter('img')->each(function($node){
                $filteredUrl = preg_replace('/^http.+(http.*)/', '$1', $node->attr('src'));
                $filteredUrl = preg_replace('/\?[^\/]+/', '', $filteredUrl);
                
                $this->arrayData['images'][] = $filteredUrl;
            });
            /* upload ảnh */
            $fileName       = $request->get('slug') ?? \App\Helpers\Charactor::randomString(20);
            self::saveImage($fileName, $request->get('hotel_info_id'), $this->arrayData['images'], 'hotel_info');
        }
        return json_encode($imagesReal);
    }

    public static function saveImage($imageName, $referenceId, $urlImages, $referenceType = 'hotel_info'){
        $i                  = 0;
        $imageAlreadyUpload = [];
        foreach ($urlImages as $urlImage) {
            /*  folder upload */
            $folderUpload   = config('admin.images.folderHotel');
            /* upload ảnh normal */
            $extension      = config('admin.images.extension');
            $name           = $imageName.'-'.$i.'-'.time();
            $fileName       = $folderUpload.$name.'.'.$extension;
            /* đưa vào job */
            $data           = [
                'url_image'         => $urlImage,
                'file_name'         => $fileName,
                'reference_type'    => $referenceType,
                'reference_id'      => $referenceId
            ];
            $imageAlreadyUpload[] = DownloadHotelImageToCloudStorage::dispatch($data);
            ++$i;
        }
        return $imageAlreadyUpload;
    }

    public static function convertToCollectionRecursive($array = []){
        $result = [];

        if(!empty($array)){
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $result[$key] = self::convertToCollectionRecursive($value);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return collect($result);
    }
}

