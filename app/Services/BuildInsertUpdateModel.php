<?php

namespace App\Services;

use App\Models\TourLocation;
use App\Models\Seo;
use Illuminate\Support\Facades\Auth;

class BuildInsertUpdateModel {
    public static function buildArrayTableSeo($dataForm, $type, $dataImage = null){
        /* update page_info
            + title
            + description
            + image (null => update)
            + image_small (null => update)
            + level
            + parent
            + ordering
            + topic
            + seo_title
            + seo_description
            + slug
            + rating_author_name
            + rating_author_star
            + rating_aggregate_count
            + rating_aggregate_star
        */
        $result                                 = [];
        if(!empty($dataForm)){
            $result['title']                    = $dataForm['title'] ?? null;
            $result['description']              = $dataForm['description'] ?? null;
            if(!empty($dataImage['filePathNormal'])) $result['image']           = $dataImage['filePathNormal'];
            if(!empty($dataImage['filePathSmall']))  $result['image_small']     = $dataImage['filePathSmall'];
            // page level
            $pageLevel                          = 1;
            $pageParent                         = 0;
            if(!empty($dataForm['parent'])){
                $infoPageParent                 = Seo::find($dataForm['parent']);
                $pageLevel                      = !empty($infoPageParent->level) ? ($infoPageParent->level+1) : $pageLevel;
                $pageParent                     = $infoPageParent->id;
            }
            $result['level']                    = $pageLevel;
            $result['parent']                   = $pageParent;
            $result['ordering']                 = $dataForm['ordering'] ?? null;
            $result['topic']                    = null;
            $result['seo_title']                = $dataForm['seo_title'] ?? $dataForm['title'] ?? null;
            $result['seo_description']          = $dataForm['seo_description'] ?? $dataForm['description'] ?? null;
            $result['slug']                     = $dataForm['slug'];
            $result['type']                     = $type;
            $result['rating_author_name']       = 1;
            $result['rating_author_star']       = 5;
            $result['rating_aggregate_count']   = $dataForm['rating_aggregate_count'] ?? 0;
            $result['rating_aggregate_star']    = $dataForm['rating_aggregate_star'] ?? null;
            $result['created_by']               = Auth::id() ?? 0;
        }
        return $result;
    }

    public static function buildArrayTableTourLocation($dataForm, $pageId = null){
        /* upload tour_location
            + name
            + seo_id
            + region_id
            + province_id
            + district_id
        */
        $result                             = [];
        if(!empty($dataForm)){
            $result['name']                 = $dataForm['title'] ?? null;
            $result['description']          = $dataForm['description'] ?? null;
            $result['content']              = $dataForm['content'] ?? null;
            if(!empty($pageId)) $result['seo_id'] = $pageId;
            $result['region_id']            = $dataForm['region'];
            $result['island']               = !empty($dataForm['island']) ? 1 : 0;
            $result['province_id']          = $dataForm['province'];
            $result['district_id']          = $dataForm['district'] ?? null;
        }
        return $result;
    }

    public static function buildArrayTableTourDeparture($dataForm, $pageId = null){
        /* upload tour_departure
            + name
            + seo_id
            + region_id
            + province_id
            + district_id
        */
        $result                             = [];
        if(!empty($dataForm)){
            $result['name']                 = $dataForm['title'] ?? null;
            $result['description']          = $dataForm['description'] ?? null;
            $result['content']              = $dataForm['content'] ?? null;
            if(!empty($pageId)) $result['seo_id'] = $pageId;
            $result['region_id']            = $dataForm['region'];
        }
        return $result;
    }

    public static function buildArrayTableTourInfo($dataForm, $seoId = null){
        /* 
            tour_departure_id
            code
            name
            price_show
            price_del
            departure_schedule
            days
            nights
            time_start
            time_end
            pick_up
            status_show
            status_sidebar
            content
        */
        $result     = [];
        if(!empty($dataForm)){
            if(!empty($seoId)) $result['seo_id'] = $seoId;
            $result['tour_departure_id']    = $dataForm['tour_departure_id'] ?? null;
            $result['code']                 = $dataForm['code'] ?? null;
            $result['name']                 = $dataForm['title'] ?? null;
            $result['price_show']           = $dataForm['price_show'] ?? 0;
            $result['price_del']            = $dataForm['price_del'] ?? 0;
            $result['departure_schedule']   = $dataForm['departure_schedule'];
            $result['days']                 = $dataForm['days'] ?? 0;
            $result['nights']               = $dataForm['nights'] ?? 0;
            $result['time_start']           = $dataForm['time_start'] ?? null;
            $result['time_end']             = $dataForm['time_end'] ?? null;
            $result['pick_up']              = $dataForm['pick_up'] ?? null;
            $result['status_show']          = !empty($dataForm['status_show']) ? 1 : 0;
            $result['status_sidebar']       = !empty($dataForm['status_sidebar']) ? 1 : 0;
            $result['content']              = $dataForm['content'] ?? null;
        }
        return $result;
    }

    public static function buildArrayTableStaffInfo($dataForm, $avatarPath = null){
        /*
            fullname
            prefix_name
            phone
            zalo
            email
            avatar
        */
        $result                     = [];
        if(!empty($dataForm)){
            $result['firstname']    = $dataForm['firstname'] ?? null;
            $result['lastname']     = $dataForm['lastname'] ?? null;
            $result['prefix_name']  = $dataForm['prefix_name'] ?? null;
            $result['phone']        = $dataForm['phone'] ?? null;
            $result['zalo']         = $dataForm['zalo'] ?? null;
            $result['email']        = $dataForm['email'] ?? null;
            if(!empty($avatarPath)) $result['avatar'] = $avatarPath;
        }
        return $result;
    }

    public static function buildArrayTablePartnerInfo($dataForm, $logoPath = null){
        /*
            name
            company_name
            company_code
            company_address
            company_website
            company_hotline
            company_email
            company_logo
            created_by
        */
        $result                         = [];
        if(!empty($dataForm)){
            $result['name']             = $dataForm['name'] ?? null;
            $result['company_name']     = $dataForm['company_name'] ?? null;
            $result['company_code']     = $dataForm['company_code'] ?? null;
            $result['company_address']  = $dataForm['company_address'] ?? null;
            $result['company_website']  = $dataForm['company_website'] ?? null;
            $result['company_hotline']  = $dataForm['company_hotline'] ?? null;
            $result['company_email']    = $dataForm['company_email'] ?? null;
            $result['created_by']       = Auth::id() ?? 0;
            if(!empty($logoPath)) $result['company_logo'] = $logoPath;
        }
        return $result;
    }

    public static function buildArrayTablePartnerContact($dataForm){
         /*
            partner_id
            name
            address
            phone
            zalo
            email
            # default
        */
        $result                         = [];
        if(!empty($dataForm)&&!empty($dataForm['partner_id'])){
            $result['partner_id']       = $dataForm['partner_id'];
            $result['name']             = $dataForm['name'] ?? null;
            $result['address']          = $dataForm['address'] ?? null;
            $result['phone']            = $dataForm['phone'] ?? null;
            $result['zalo']             = $dataForm['zalo'] ?? null;
            $result['email']            = $dataForm['email'] ?? null;
        }
        return $result;
    }

    public static function buildArrayTableTourOption($dataForm){
        /*
            tour_info_id
            option
            apply_day
        */
        $result     = [];
        if(!empty($dataForm)&&!empty($dataForm['tour_info_id'])){
            $result['tour_info_id']     = $dataForm['tour_info_id'];
            $result['option']           = $dataForm['option'] ?? null;
            $result['apply_day']        = $dataForm['apply_day'] ?? null;
        }
        return $result;
    }

    public static function buildArrayTableCustomerInfo($dataForm){
        /*
            prefix_name
            name
            phone
            zalo
            email
        */
        $result     = [];
        if(!empty($dataForm)){
            $result['prefix_name']      = $dataForm['prefix_name'] ?? null;
            $result['name']             = $dataForm['name'] ?? null;
            $result['phone']            = $dataForm['phone'] ?? null;
            $result['zalo']             = $dataForm['zalo'] ?? null;
            $result['email']            = $dataForm['email'] ?? null;
        }
        return $result;
    }

    public static function buildArrayTableTourBooking($idCustomer, $dataForm, $noBooking = null){
        /*
            no
            customer_info_id
            tour_info_id
            tour_booking_status_id
            tour_option_id
            paid
            note_customer
            created_by
        */
        $result                                 = [];
        if(!empty($dataForm)){
            if(!empty($noBooking)) $result['customer_info_id'] = $noBooking;
            $result['customer_info_id']         = $idCustomer;
            $result['tour_info_id']             = $dataForm['tour_info_id'];
            if(!empty($dataForm['tour_booking_status_id'])) $result['tour_booking_status_id'] = $dataForm['tour_booking_status_id'];
            $result['tour_option_id']           = $dataForm['tour_option_id'] ?? null;
            $result['departure_day']            = $dataForm['departure_day'];
            $result['paid']                     = $dataForm['paid'] ?? null;
            $result['note_customer']            = $dataForm['note_customer'] ?? null;
            $result['created_by']               = Auth::id() ?? 0;
        }
        return $result;
    }

    public static function buildArrayTableCitizenIdentityInfoTable($idBooking, $typeBooking, $dataForm){
        /*
            tour_booking_id | ship_booking_id | room_booking_id | service_booking_id
            customer_name
            customer_identity
            customer_year_of_birth

            trả về mảng các thông tin khách hàng hợp lệ
        */
        $result                                             = [];
        if(!empty($idBooking)&&!empty($typeBooking)&&!empty($dataForm['customer_list'])){
            $i                                              = 0;
            foreach($dataForm['customer_list'] as $customer){
                if(!empty($customer['customer_name'])&&!empty($customer['customer_year_of_birth'])){
                    $result[$i][$typeBooking]               = $idBooking;
                    $result[$i]['customer_name']            = $customer['customer_name'] ?? null;
                    $result[$i]['customer_identity']        = $customer['customer_identity'] ?? null;
                    $result[$i]['customer_year_of_birth']   = $customer['customer_year_of_birth'] ?? null;
                    ++$i;
                }
            }
        }
        return $result;
    }

    public static function buildArrayTableCostMoreLess($idReference, $typeReference, $dataForm, $type = 'insert'){
        /*
            type
            name
            quantity
            unit_price
            reference_id
            reference_type
            created_by
        */
        $result                                 = [];
        if(!empty($dataForm)){
            $result['name']                     = $dataForm['name'];
            $result['quantity']                 = $dataForm['quantity'];
            $result['unit_price']               = $dataForm['unit_price'];
            $result['reference_id']             = $idReference;
            $result['reference_type']           = $typeReference;
            if($type=='insert') $result['created_by'] = Auth::id() ?? 0;
        }
        return $result;
    }

    public static function buildArrayTableVatInfo($idTourBooking, $reference, $dataForm, $type = 'insert'){
        /*
            tour_booking_id
            vat_name
            vat_code
            vat_address
            vat_note
            created_by
        */
        $result                                 = [];
        if(!empty($idTourBooking)&&!empty($reference)&&!empty($dataForm)){
            $result['reference_id']             = $idTourBooking;
            $result['reference_type']           = $reference;
            $result['vat_name']                 = $dataForm['vat_name'];
            $result['vat_code']                 = $dataForm['vat_code'];
            $result['vat_address']              = $dataForm['vat_code'];
            $result['vat_note']                 = $dataForm['vat_note'];
            if($type=='insert') $result['created_by'] = Auth::id() ?? 0;
        }
        return $result;
    }
}