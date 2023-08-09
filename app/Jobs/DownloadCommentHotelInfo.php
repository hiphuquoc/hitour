<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Hotel;
use App\Models\Comment;
use App\Services\BuildInsertUpdateModel;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;


class DownloadCommentHotelInfo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    private $number;
    private $infoHotel;

    public function __construct($idHotel, $downloadFormNumber){
        // $this->BuildInsertUpdateModel   = $BuildInsertUpdateModel;
        $this->id                       = $idHotel;
        $this->number                   = $downloadFormNumber;

        $this->infoHotel                = Hotel::select('*')
                                            ->where('id', $this->id)
                                            ->with('seo')
                                            ->first();
    }

    public function handle(){
        /* Tạo đối tượng Client của Goutte */
        $client         = new Client();
        $everyTime      = 5; /* đây là số comment mặc định trên mỗi trang của tripadvisor */
        /* Gửi yêu cầu GET đến URL cần lấy dữ liệu */
        $url            = explode('Reviews', $this->infoHotel->url_crawler_tripadvisor);
        $url            = implode('Reviews-or'.$this->number, $url);
        try {
            $crawlerContent = $client->request('GET', $url);
            /* lấy comment */
            if($crawlerContent->filter('[data-test-target=reviews-tab] .YibKl')->count()>0){
                $crawlerContent->filter('[data-test-target=reviews-tab] .YibKl')->each(function($node){
                    $tmpInsertComment  = [];
                    /* số sao */
                    $rating         = preg_replace("/[^0-9]/", '', $node->filter('[data-test-target=review-rating] > span')->attr('class'));
                    $tmpInsertComment['rating']    = $rating/10;
                    /* người đánh giá + lúc đánh giá */
                    $tmp            = $node->filter('.cRVSd')->text();
                    $tmp            = explode('đã viết đánh giá vào', $tmp);
                    $authorName     = $tmp[0];
                    /* xử lý ngày tháng comment */
                    $chuoiNgayThang = $tmp[1];
                    $mangChuoi      = explode(" ", $chuoiNgayThang);
                    $thang          = 0;
                    $tungay         = array("thg", " ", "1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12");
                    $denngay        = array("", "", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                    $mangChuoi[1]   = str_replace($tungay, $denngay, strtolower($mangChuoi[1]));
                    $thang          = (int)$mangChuoi[1];
                    $tmpInsertComment['created_at']    = sprintf("%d-%02d-%02d", (int)$mangChuoi[2], $thang, 01);

                    $tmpInsertComment['author_name']   = $authorName;
                    /* tiêu đề đánh giá */
                    $tmpInsertComment['title']         = $node->filter('.Qwuub')->text();
                    /* nội dung đánh giá */
                    $tmpInsertComment['comment']       = $node->filter('.fIrGe')->text();
                    /* lưu vào cơ sở dữ liệu */
                    $insertComment = BuildInsertUpdateModel::buildArrayTableCommentInfo($tmpInsertComment, $this->infoHotel->id, 'hotel_info');
                    Comment::insertItem($insertComment);
                });
                DownloadCommentHotelInfo::dispatch($this->id, ($this->number + $everyTime));
            }else {
                return true;
            }
        }catch (\Exception $e) {
            return $url;
        }
    }
}
