<?php

namespace App\Services;
use App\Models\Seo;
use Illuminate\Support\Facades\Auth;

use App\Models\Ship;
use App\Models\ShipPort;
use App\Models\ShipPrice;

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
            /* slug full */
            $result['slug_full']                = Seo::buildFullUrl($dataForm['slug'], $pageLevel, $pageParent);
            /* type */
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
            $result['display_name']         = $dataForm['display_name'] ?? null;
            if(!empty($pageId)) $result['seo_id'] = $pageId;
            $result['region_id']            = $dataForm['region'];
            $result['island']               = !empty($dataForm['island']) ? 1 : 0;
            $result['province_id']          = $dataForm['province'] ?? null;
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
            $result['display_name']         = $dataForm['display_name'] ?? null;
            if(!empty($pageId)) $result['seo_id'] = $pageId;
            $result['region_id']            = $dataForm['region'];
            $result['province_id']          = $dataForm['province'] ?? null;
            $result['district_id']          = $dataForm['district'] ?? null;
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
            transport
            status_show
            status_sidebar
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
            $result['transport']            = $dataForm['transport'] ?? null;
            $result['status_show']          = !empty($dataForm['status_show']) ? 1 : 0;
            $result['status_sidebar']       = !empty($dataForm['status_sidebar']) ? 1 : 0;
        }
        return $result;
    }

    public static function buildArrayTableTourContent($dataForm, $tourInfoId){
        /* 
            tour_info_id
            special_content
            special_list
            include
            not_include
            policy_child
            policy_cancel
            menu
            hotel
            note
        */
        $result     = [];
        if(!empty($dataForm)&&!empty($tourInfoId)){
            $result['tour_info_id']         = $tourInfoId;
            $result['special_content']      = $dataForm['special_content'] ?? null;
            $result['special_list']         = $dataForm['special_list'] ?? null;
            $result['include']              = $dataForm['include'] ?? null;
            $result['not_include']          = $dataForm['not_include'] ?? null;
            $result['policy_child']         = $dataForm['policy_child'] ?? null;
            $result['menu']                 = $dataForm['menu'] ?? null;
            $result['hotel']                = $dataForm['hotel'] ?? null;
            $result['policy_cancel']        = $dataForm['policy_cancel'] ?? null;
            $result['note']                 = $dataForm['note'] ?? null;
        }
        return $result;
    }

    public static function buildArrayTableShipLocation($dataForm, $pageId = null){
        /* upload ship_location
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
            $result['display_name']         = $dataForm['display_name'] ?? null;
            if(!empty($pageId)) $result['seo_id'] = $pageId;
            $result['region_id']            = $dataForm['region'];
            $result['province_id']          = $dataForm['province'] ?? null;
            $result['district_id']          = $dataForm['district'] ?? null;
        }
        return $result;
    }

    public static function buildArrayTableShipDeparture($dataForm, $pageId = null){
        /* upload ship_departure
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
            $result['display_name']         = $dataForm['display_name'] ?? null;
            if(!empty($pageId)) $result['seo_id'] = $pageId;
            $result['region_id']            = $dataForm['region'];
            $result['province_id']          = $dataForm['province'] ?? null;
            $result['district_id']          = $dataForm['district'] ?? null;
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

    public static function buildArrayTableTourPartner($dataForm, $logoPath = null){
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

    public static function buildArrayTableTourPartnerContact($dataForm){
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
        */
        $result     = [];
        if(!empty($dataForm)&&!empty($dataForm['tour_info_id'])){
            $result['tour_info_id']     = $dataForm['tour_info_id'];
            $result['option']           = $dataForm['option'] ?? null;
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

    public static function buildArrayTableShipPartner($dataForm, $seoId = null, $logoPath = null){
        /*
            seo_id
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
            if(!empty($seoId)) $result['seo_id'] = $seoId;
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

    public static function buildArrayTableShipPartnerContact($dataForm){
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

    public static function buildArrayTableShipInfo($dataForm, $seoId = null){
        /* 
            seo_id
            name
            ship_location_id
            ship_departure_id
            note
            created_by
        */
        $result     = [];
        if(!empty($dataForm)){
            if(!empty($seoId)) $result['seo_id'] = $seoId;
            $result['name']                     = $dataForm['title'];
            $result['ship_departure_id']        = $dataForm['ship_departure_id'];
            $result['ship_port_departure_id']   = $dataForm['ship_port_departure_id'];
            $result['ship_location_id']         = $dataForm['ship_location_id'];
            $result['ship_port_location_id']    = $dataForm['ship_port_location_id'];
            $result['note']                     = $dataForm['note'] ?? null;
            $result['created_by']               = Auth::id() ?? 0;
        }
        return $result;
    }

    public static function buildArrayTableShipPrice($dataForm){
        /* 
            ship_info_id
            ship_partner_id
            date_start *********
            date_end *********
            price_adult
            price_child
            price_old
            price_vip
            profit_percent
        */
        $result     = [];
        if(!empty($dataForm)){
            $result['ship_info_id']         = $dataForm['ship_info_id'];
            $result['ship_partner_id']      = $dataForm['ship_partner_id'];
            /* date_start and date_end */
            $arrDate                        = explode('to', $dataForm['date_range']);
            $result['date_start']           = $arrDate[0];
            $result['date_end']             = !empty($arrDate[1]) ? $arrDate[1] : $arrDate[0];
            $result['price_adult']          = $dataForm['price_adult'];
            $result['price_child']          = $dataForm['price_child'];
            $result['price_old']            = $dataForm['price_old'];
            $result['price_vip']            = $dataForm['price_vip'] ?? null;
            $result['profit_percent']       = $dataForm['profit_percent'];
        }
        return $result;
    }

    public static function buildArrayTableShipTime($idShipPrice, $dataForm){
        /* này khác những cái trên => trả về 1 mảng nhiều phần tử insert */
        $result                                 = [];
        if(!empty($idShipPrice)&&!empty($dataForm)){
            for($i=0;$i<count($dataForm['time_departure']);++$i){
                $result[$i]['ship_price_id']    = $idShipPrice;
                $result[$i]['time_departure']   = date('H:i', strtotime($dataForm['time_departure'][$i]));
                $result[$i]['time_arrive']      = date('H:i', strtotime($dataForm['time_arrive'][$i]));
                /* time_move */
                $result[$i]['time_move']        = \App\Helpers\Time::calcTimeMove($dataForm['time_departure'][$i], $dataForm['time_arrive'][$i]);
                /* from and to */
                $result[$i]['name']             = $dataForm['string_from_to'][$i];
                $arrFromTo                      = explode('-', $dataForm['string_from_to'][$i]);
                $from                           = trim($arrFromTo[0]);
                $result[$i]['ship_from']        = $from;
                $fromSort                       = null;
                $tmp                            = explode(' ', $from);
                foreach($tmp as $t) $fromSort   .= strtoupper(mb_substr($t, 0, 1));
                $result[$i]['ship_from_sort']   = $fromSort;
                $to                             = trim($arrFromTo[1]);
                $result[$i]['ship_to']          = $to;
                $toSort                         = null;
                $tmp                            = explode(' ', $to);
                foreach($tmp as $t) $toSort     .= strtoupper(mb_substr($t, 0, 1));
                $result[$i]['ship_to_sort']     = $toSort;
            }
        }
        return $result;
    }

    public static function buildArrayTableShipPort($dataForm){
        /* 
            name
            address
            district_id
            province_id
            region_id
        */
        $result                     = [];
        if(!empty($dataForm)){
            $result['name']         = $dataForm['name'];
            $result['address']      = $dataForm['address'];
            $result['district_id']  = $dataForm['district'];
            $result['province_id']  = $dataForm['province'];
            $result['region_id']    = $dataForm['region'];
        }
        return $result;
    }

    public static function buildArrayTableShipBooking($idCustomer, $dataForm){
        $result = [];
        if(!empty($dataForm)){
            $result['no']                   = \App\Helpers\Charactor::randomString(10);
            if(!empty($idCustomer)) $result['customer_info_id'] = $idCustomer;
            if(!empty($dataForm['ship_booking_status_id'])) $result['ship_booking_status_id'] = $dataForm['ship_booking_status_id'];
            $result['note_customer']        = $dataForm['note_customer'] ?? null;
            $result['created_by']           = Auth::id() ?? 0;
        }
        return $result;
    }

    public static function buildArrayTableShipQuantityAndPrice($idBooking, $dataForm){
        $result = [];
        if(!empty($dataForm)){
            /* chỗ này cần lấy departure->display_name và location->display_name nên chỉ cần lấy 1 chuyến đi (chuyến về đảo lại) */
            $infoShip1                      = Ship::select('*')
                                                ->where('id', $dataForm['ship_info_id_1'])
                                                ->with('departure', 'location')
                                                ->first();
            $infoPortDeparture1             = ShipPort::select('*')
                                                ->where('id', $dataForm['ship_port_departure_id_1'])
                                                ->with('district', 'province')
                                                ->first();
            $infoPortLocation1              = ShipPort::select('*')
                                                ->where('id', $dataForm['ship_port_location_id_1'])
                                                ->with('district', 'province')
                                                ->first();
            /* chuyến đi */
            if(!empty($idBooking)) $result[0]['ship_booking_id'] = $idBooking;
            $result[0]['date']              = $dataForm['date_1'];
            $result[0]['port_departure']    = $infoPortDeparture1->name;
            $result[0]['port_departure_address']    = $infoPortDeparture1->address;
            $result[0]['port_departure_district']   = $infoPortDeparture1->district->district_name;
            $result[0]['port_departure_province']   = $infoPortDeparture1->province->province_name;
            $result[0]['port_location']     = $infoPortLocation1->name;
            $result[0]['port_location_address']     = $infoPortLocation1->address;
            $result[0]['port_location_district']    = $infoPortLocation1->district->district_name;
            $result[0]['port_location_province']    = $infoPortLocation1->province->province_name;
            $result[0]['departure']         = $infoShip1->departure->display_name;
            $result[0]['location']          = $infoShip1->location->display_name;
            $result[0]['quantity_adult']    = $dataForm['quantity_adult_1'] ?? 0;
            $result[0]['quantity_child']    = $dataForm['quantity_child_1'] ?? 0;
            $result[0]['quantity_old']      = $dataForm['quantity_old_1'] ?? 0;
            $tmp                            = explode('|', $dataForm['dp1']);
            $infoShipPrice                  = ShipPrice::select('*')
                                                ->where('id', $tmp[0])
                                                ->with('partner')
                                                ->first();
            $result[0]['partner_name']      = $infoShipPrice->partner->name;
            $result[0]['price_adult']       = $infoShipPrice->price_adult;
            $result[0]['price_child']       = $infoShipPrice->price_child;
            $result[0]['price_old']         = $infoShipPrice->price_old;
            $result[0]['time_departure']    = $tmp[1] ?? null;
            $result[0]['time_arrive']       = $tmp[2] ?? null;
            $result[0]['type']              = $tmp[3] ?? null;
            /* chuyến về */
            if(!empty($dataForm['ship_info_id_2'])&&$dataForm['type_booking']==2&&!empty($dataForm['ship_port_departure_id_2'])&&!empty($dataForm['ship_port_location_id_2'])){
                /* chỗ này cần lấy departure->display_name và location->display_name nên chỉ cần lấy 1 chuyến đi (chuyến về đảo lại) */
                $infoShip2                      = Ship::select('*')
                                                    ->where('id', $dataForm['ship_info_id_2'])
                                                    ->with('departure', 'location')
                                                    ->first();
                $infoPortDeparture2             = ShipPort::select('*')
                                                    ->where('id', $dataForm['ship_port_departure_id_2'])
                                                    ->with('district', 'province')
                                                    ->first();
                $infoPortLocation2              = ShipPort::select('*')
                                                    ->where('id', $dataForm['ship_port_location_id_2'])
                                                    ->with('district', 'province')
                                                    ->first();
                if(!empty($idBooking)) $result[1]['ship_booking_id'] = $idBooking;
                $result[1]['date']              = $dataForm['date_2'];
                $result[1]['port_departure']    = $infoPortDeparture2->name;
                $result[1]['port_departure_address']    = $infoPortDeparture2->address;
                $result[1]['port_departure_district']   = $infoPortDeparture2->district->district_name;
                $result[1]['port_departure_province']   = $infoPortDeparture2->province->province_name;
                $result[1]['port_location']     = $infoPortLocation2->name;
                $result[1]['port_location_address']     = $infoPortLocation2->address;
                $result[1]['port_location_district']    = $infoPortLocation2->district->district_name;
                $result[1]['port_location_province']    = $infoPortLocation2->province->province_name;
                $result[1]['departure']         = $infoShip2->departure->display_name;
                $result[1]['location']          = $infoShip2->location->display_name;
                if(!empty($dataForm['quantity_adult_2'])&&$dataForm['quantity_child_2']&&!empty($dataForm['quantity_old_2'])){
                    $result[1]['quantity_adult']        = $dataForm['quantity_adult_2'];
                    $result[1]['quantity_child']        = $dataForm['quantity_child_2'];
                    $result[1]['quantity_old']          = $dataForm['quantity_old_2'];
                }else {
                    $result[1]['quantity_adult']        = $dataForm['quantity_adult_1'] ?? 0;
                    $result[1]['quantity_child']        = $dataForm['quantity_child_1'] ?? 0;
                    $result[1]['quantity_old']          = $dataForm['quantity_old_1'] ?? 0;
                }
                $tmp                            = explode('|', $dataForm['dp2']);
                $infoShipPrice                  = ShipPrice::select('*')
                                                ->where('id', $tmp[0])
                                                ->with('partner')
                                                ->first();
                $result[1]['partner_name']      = $infoShipPrice->partner->name;
                $result[1]['price_adult']       = $infoShipPrice->price_adult;
                $result[1]['price_child']       = $infoShipPrice->price_child;
                $result[1]['price_old']         = $infoShipPrice->price_old;
                $result[1]['time_departure']    = $tmp[1] ?? null;
                $result[1]['time_arrive']       = $tmp[2] ?? null;
                $result[1]['type']              = $tmp[3] ?? null;
            }
        }
        return $result;
    }

    public static function buildArrayTableGuideInfo($dataForm, $pageId = null){
        /* upload guide_info
            + name
            + description
            + display_name
            + seo_id
            + region_id
            + province_id
            + district_id
        */
        $result                             = [];
        if(!empty($dataForm)){
            $result['name']                 = $dataForm['title'] ?? null;
            $result['description']          = $dataForm['description'] ?? null;
            $result['display_name']         = $dataForm['display_name'] ?? null;
            if(!empty($pageId)) $result['seo_id'] = $pageId;
            $result['region_id']            = $dataForm['region'];
            $result['province_id']          = $dataForm['province'] ?? null;
            $result['district_id']          = $dataForm['district'] ?? null;
        }
        return $result;
    }
}