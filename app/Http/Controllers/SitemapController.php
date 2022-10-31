<?php

namespace App\Http\Controllers;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
use App\Models\Seo;

class SitemapController extends Controller {

    public static function main(){
        $tmp        = Seo::all();
        $arrayTable = [];
        foreach($tmp as $item){
            if(!empty($item->type)&&!in_array($item->type, $arrayTable)) $arrayTable[] = $item->type;
        }
        /* viết dữ liệu */
        $sitemapXhtml       = '<urlset xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach($arrayTable as $item){
            $url            = env('APP_URL').'/sitemap/'.$item.'.xml';
            $mk             = time() - rand(3600, 259200);
            $sitemapXhtml   .= '<url>
                                    <loc>'.$url.'</loc>
                                    <lastmod>'.date('c', $mk).'</lastmod>
                                    <changefreq>weekly</changefreq>
                                    <priority>0.8</priority>
                                </url>';
        }
        $sitemapXhtml       .= '</urlset>';
        /* response */
        return response()->make($sitemapXhtml)->header('Content-Type', 'application/xml');
    }

    public static function child($name){
        if(!empty($name)){
            $tmp    = Seo::select('*')
                        ->where('type', $name)
                        ->get();
            if(!empty($tmp)&&$tmp->isNotEmpty()){
                $sitemapXhtml       = '<urlset xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
                foreach($tmp as $item){
                    $sitemapXhtml   .= '<url>
                                            <loc>'.env('APP_URL').'/'.$item->slug_full.'</loc>
                                            <lastmod>'.date('c', strtotime($item->updated_at)).'</lastmod>
                                            <changefreq>hourly</changefreq>
                                            <priority>1</priority>
                                            <image:image>
                                                <image:loc>'.env('APP_URL').$item->image.'</image:loc>
                                                <image:title>'.self::replaceSpecialCharactorXml($item->seo_title).'</image:title>
                                            </image:image>
                                        </url>';
                }
                $sitemapXhtml       .= '</urlset>';
                return response()->make($sitemapXhtml)->header('Content-Type', 'application/xml');
            }
        }
        /* return 404 */
        return \App\Http\Controllers\ErrorController::error404();
    }

    public static function replaceSpecialCharactorXml($str){
        $output         = null;
        if(!empty($str)){
            $dataEscape = [
                '&' => '&amp;',
                '<' => '&lt;',
                '>' => '&gt;',
                '"' => '&quot;',
                "'" => '&apos;'
            ];
            $output     = $str;
            foreach($dataEscape as $key => $value){
                $output = preg_replace('#'.$key.'#imsU', $value, $output);
            }
        }
        return $output;
    }
}
