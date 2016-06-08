<?php

class ModelMain {
	
	public function getVipRequests($country_id = 1) {
		$query = "
		SELECT
		    IF( a.country_id = 1, 'ua', IF( a.country_id = 2, 'ru', 'by' ) ) AS country_code, a.*,
		    vr.type, vr.date_add AS request_date,
		    ui.contact_phones AS user_phones, s.name AS section_name
		FROM
		(
            (
                SELECT
                    country_id,
                    activity.activity_id AS resource_id,
                    5 AS section_id,
                    'activity' As type,
                    activity.user_id,
                    activity.user_name,
                    activity.name,
                    activity.flag_vip_add,
                    date_add
                FROM `activity`
                WHERE flag_delete = 0 -- AND country_id = $country_id
                AND IF(date_start = '0000-00-00', 1,
                    IF(date_end != '0000-00-00', date_end > '" . DB::now(1) . "', date_start > '" . DB::now(1) . "')
                )
            ) UNION (
                SELECT
                    country_id,
                    ads.ads_id AS resource_id,
                    4 AS section_id,
                    'ads' As type,
                    user_id,
                    user_name,
                    product_name AS name,
                    flag_vip_add,
                    date_add
                FROM `ads`
                WHERE ads.flag_delete = 0 -- AND ads.country_id = $country_id
            ) UNION (
                SELECT
                    country_id,
                    p.product_new_id AS resource_id,
                    3 AS section_id,
                    'products_new' As type,
                    user_id,
                    user_name,
                    product_name AS name,
                    flag_vip_add,
                    date_add
                FROM `products_new` AS p
                WHERE p.flag_delete = 0 -- AND p.country_id = $country_id
            ) UNION (
                SELECT
                    country_id,
                    s.service_id AS resource_id,
                    9 AS section_id,
                    'services' AS type,
                    user_id,
                    user_name,
                    name,
                    flag_vip_add,
                    date_add
                FROM `services` AS s
                WHERE s.flag_delete = 0 -- AND s.country_id = $country_id
            ) UNION (
                SELECT
                    country_id,
                    d.demand_id AS resource_id,
                    11 AS section_id,
                    'demand' AS type,
                    user_id,
                    user_name,
                    name,
                    flag_vip_add,
                    date_add
                FROM `demand` AS d
                WHERE d.flag_delete = 0 -- AND d.country_id = $country_id
            ) UNION (
                SELECT
                    country_id,
                    l.lab_id AS resource_id,
                    7 AS section_id,
                    'labs' AS type,
                    user_id,
                    (SELECT name FROM `users_info` WHERE user_id = l.user_id) AS user_name,
                    name,
                    flag_vip_add,
                    date_add
                FROM `labs` AS l
                WHERE l.flag_delete = 0 -- AND l.country_id = $country_id
            ) UNION (
                SELECT
                    country_id,
                    r.realty_id AS resource_id,
                    8 AS section_id,
                    'realty' AS type,
                    user_id,
                    user_name,
                    CONCAT(name, ', г. ', city_name) AS name,
                    flag_vip_add,
                    date_add
                FROM `realty` AS r
                WHERE r.flag_delete = 0 -- AND r.country_id = $country_id
            ) UNION (
                SELECT
                    country_id,
                    d.diagnostic_id AS resource_id,
                    10 AS section_id,
                    'diagnostic' AS type,
                    user_id,
                    user_name,
                    name,
                    flag_vip_add,
                    date_add
                FROM `diagnostic` AS d
                WHERE d.flag_delete = 0 -- AND d.country_id = $country_id
            ) UNION (
                SELECT
                    country_id,
                    w.work_id AS resource_id,
                    6 AS section_id,
                    'resume' AS type,
                    user_id,
                    user_name,
                    (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = w.work_id)) AS name,
                    flag_vip_add,
                    date_add
                FROM `work` AS w
                WHERE w.flag_delete = 0 -- AND w.country_id = $country_id
            ) UNION (
                SELECT
                    country_id,
                    v.vacancy_id AS resource_id,
                    15 AS section_id,
                    'vacancies'	AS type,
                    v.user_id,
                    c.name AS user_name,
                    CONCAT((SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)), ', г. ', city_name) AS name,
                    flag_vip_add,
                    date_add
                FROM `vacancies` AS v
                INNER JOIN `vacancy_company_info` AS c USING(company_id)
                WHERE v.flag_delete = 0 -- AND v.country_id = $country_id
            )
		) AS a
		INNER JOIN `vip_requests` `vr` USING( resource_id )
		INNER JOIN `users_info` `ui` USING( user_id )
		INNER JOIN `sections` `s` ON s.section_id = a.section_id
		WHERE a.flag_vip_add = 1
		ORDER BY a.date_add DESC
		";

        $items  = \DB::getAssocGroup( $query );

        foreach( $items as & $item ) {
            $item = array_map( function( $row ){
                $row['user_phones'] = join( "\n", explode( ',', $row['user_phones'] ) );
                $row['type_name']   = $row['type'] == 1 ? 'Самолет' : $row['type'] == 2 ? 'Машинка' : 'Велосипед';
                $row['url']			= Site::getSectionUrlById($row['section_id']);
                return $row;
            }, $item );
        }

        return $items;

	}

    public function getVipAds() {

        $query = "
		SELECT
		    IF( a.country_id = 1, 'ua', IF( a.country_id = 2, 'ru', 'by' ) ) AS country_code, a.*,
		    x.*, CONCAT( a.resource_id, a.section_id ) AS row_key, MIN(x.ads_type) min_type,
		    ui.contact_phones AS user_phones, s.name AS section_name,
		    DATEDIFF( x.date_end, x.date_start ) AS date_diff,
		    DATEDIFF( x.date_end, NOW() ) AS days_left
		FROM
		(
            (
                SELECT
                    country_id,
                    activity.activity_id AS resource_id,
                    5 AS section_id,
                    'activity' As type,
                    activity.user_id,
                    activity.user_name,
                    activity.name,
                    activity.flag_vip_add
                FROM `activity`
                WHERE flag_delete = 0
                AND IF(date_start = '0000-00-00', 1,
                    IF(date_end != '0000-00-00', date_end > '" . DB::now(1) . "', date_start > '" . DB::now(1) . "')
                )
            ) UNION (
                SELECT
                    country_id,
                    ads.ads_id AS resource_id,
                    4 AS section_id,
                    'ads' As type,
                    user_id,
                    user_name,
                    product_name AS name,
                    flag_vip_add
              FROM `ads`  inner join  payment_all  ON ads.ads_id=payment_all.ads_id
				WHERE ads.flag_delete = 0  AND payment_all.status='success'
            ) UNION (
                SELECT
                    country_id,
                    p.product_new_id AS resource_id,
                    3 AS section_id,
                    'products_new' As type,
                    user_id,
                    user_name,
                    product_name AS name,
                    flag_vip_add
                FROM `products_new` AS p
                WHERE p.flag_delete = 0
            ) UNION (
                SELECT
                    country_id,
                    s.service_id AS resource_id,
                    9 AS section_id,
                    'services' AS type,
                    user_id,
                    user_name,
                    name,
                    flag_vip_add
                FROM `services` AS s
                WHERE s.flag_delete = 0
            ) UNION (
                SELECT
                    country_id,
                    d.demand_id AS resource_id,
                    11 AS section_id,
                    'demand' AS type,
                    user_id,
                    user_name,
                    name,
                    flag_vip_add
                FROM `demand` AS d
                WHERE d.flag_delete = 0
            ) UNION (
                SELECT
                    country_id,
                    l.lab_id AS resource_id,
                    7 AS section_id,
                    'labs' AS type,
                    user_id,
                    (SELECT name FROM `users_info` WHERE user_id = l.user_id) AS user_name,
                    name,
                    flag_vip_add
                FROM `labs` AS l
                WHERE l.flag_delete = 0
            ) UNION (
                SELECT
                    country_id,
                    r.realty_id AS resource_id,
                    8 AS section_id,
                    'realty' AS type,
                    user_id,
                    user_name,
                    CONCAT(name, ', г. ', city_name) AS name,
                    flag_vip_add
                FROM `realty` AS r
                WHERE r.flag_delete = 0
            ) UNION (
                SELECT
                    country_id,
                    d.diagnostic_id AS resource_id,
                    10 AS section_id,
                    'diagnostic' AS type,
                    user_id,
                    user_name,
                    name,
                    flag_vip_add
                FROM `diagnostic` AS d
                WHERE d.flag_delete = 0
            ) UNION (
                SELECT
                    country_id,
                    w.work_id AS resource_id,
                    6 AS section_id,
                    'resume' AS type,
                    user_id,
                    user_name,
                    (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = w.work_id)) AS name,
                    flag_vip_add
                FROM `work` AS w
                WHERE w.flag_delete = 0
            ) UNION (
                SELECT
                    country_id,
                    v.vacancy_id AS resource_id,
                    15 AS section_id,
                    'vacancies'	AS type,
                    v.user_id,
                    c.name AS user_name,
                    CONCAT((SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)), ', г. ', city_name) AS name,
                    flag_vip_add
                FROM `vacancies` AS v
                INNER JOIN `vacancy_company_info` AS c USING(company_id)
                WHERE v.flag_delete = 0
            )
		) a

		INNER JOIN
		(
            (
                SELECT *, '1' AS ads_type
                FROM top_to_main
            )
            UNION
            (
                SELECT *, '2' AS ads_type
                FROM top_to_section
            )
            UNION
            (
                SELECT *, '3' AS ads_type
                FROM top_to_category
            )
		) x ON a.resource_id = x.resource_id AND a.section_id = x.section_id

		INNER JOIN `users_info` `ui` USING( user_id )

		INNER JOIN `sections` `s` ON s.section_id = a.section_id

		WHERE
		    x.date_end > NOW()
        GROUP BY
            row_key
		ORDER BY
		    x.ads_type ASC
		";

        $items  = \DB::getAssocGroup( $query );

        foreach( $items as & $item ) {
            $item = array_map( function( $row ){
                $row['user_phones'] = join( "\n", explode( ',', $row['user_phones'] ) );
                $row['url']			= Site::getSectionUrlById( $row['section_id'] );
                return $row;
            }, $item );
        }

        return $items;

    }

	public function getVipMainAds() {
		
	}
	
	public function getVipSectionAds() {
		
	}
	
	public function getVipCategoryAds() {
		
	}
	
	
//------------------------------------------------------------------------------------------------------------------------	
	
	 public function getPayment($start=0 ,$limit  ){
		 
		     $date_start=Request::post('date_start');
			 $date_end=Request::post('date_end');
		     $liqpay=Request::post('liqpay','int');
			 $portmone= Request::post('portmone','int');
			 $admin=Request::post('admin','int');
			 $archive=Request::post('archive','int');
			 $aktiv=Request::post('aktiv','int');
			 $top_3=Request::post('top_3','int');
			 $user_id =Request::get('user');
			 $jurnal_cat1 =Request::post('jurnal_cat1'); 
			 $jurnal_cat2 =Request::post('jurnal_cat2'); 
			 $jurnal_cat3 =Request::post('jurnal_cat3'); 
			 $jurnal_cat4 =Request::post('jurnal_cat4');
			 $jurnal_cat5 =Request::post('jurnal_cat5');	
			 $date=DB::now(1);
			 $sta='';
		     $time_select='';
			 
			 
			 if($date_start and $date_end){
				 /* $zero=' 00:00:00';
				 $date_start.=$zero;
				 $date_end.=$zero; */
			       $time_select= " and (l.date_add Between '$date_start' AND '$date_end' ) ";
				  
			 }elseif($date_start ){
				// $date_start.=$zero;
				//$time_select= " and (l.date_add >='$date_start' ) ";
			 }elseif($date_end ){
				// $date_end.=$zero;
				//$time_select= " and (l.date_add <='$date_end') ";
			 }
			 // Site::d($time_select);
			  
			$service_payment=''; 
			$where_date='';
			if($liqpay){
				$service_payment .= ' l.service_payment="liqpay" or';
				$sta='service_payment="liqpay" or';
			}
			
			if($portmone){
				$service_payment .= ' l.service_payment="portmone" or';
				$sta .=' service_payment="portmone" or';
			}
			
			if($admin){
				$service_payment .= ' l.service_payment="admin" or';
				$sta .=' service_payment="admin" or';
			}
			
			if($service_payment){
				$service_payment=' and ('.trim($service_payment, 'or').')';
				$sta=' '.trim($sta, 'or').'';
			}else{
				
				$service_payment = 'and ( service_payment="liqpay" or service_payment="portmone" )';
			}
			
			$service_payment= ($time_select .' '.$service_payment);
			//$service_payment=$time_select;
			
		if($time_select){
		//	$where_date .=' '. $time_select;
		}
			
	    if($aktiv){
				$where_date .=' and l_c.date_start<= "'.$date.'" AND  l_c.date_end > "'.$date.'"' ;
					//var_dump($where_date);die;
		   }
	
		   if($archive){
				$where_date .=' and  l_c.date_end < "'.$date.'"' ;
		   }
	   
		   if($archive and $aktiv)
		   {
		     $where_date='';
		   }
		   
		   if($top_3){
				 $top_end = date('Y-m-d',( time() +((24*60*60)*3)  ));
				 $START_TOP=date('Y-m-d',( time()+ (24*60*60) ));
				 $where_date = " and (l_c.date_end  Between '$START_TOP' AND '$top_end' )" ;

			} 
		   
		    
		   if($user_id){ 
			    $where_date .= " WHERE  us.user_id = '$user_id' ";
              
		   }
		   
		   $jurnal_array=array();
		   if($jurnal_cat1){
			    $jurnal_array[]=$jurnal_cat1;
			   // $where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat1'";
		    }
		   if($jurnal_cat2){
			    $jurnal_array[]=$jurnal_cat2;
			   // $where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat2'";
		    }
			if($jurnal_cat3){
				 $jurnal_array[]=$jurnal_cat3;
			    //$where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat3'";
		    }
			if($jurnal_cat4){
				$jurnal_array[]=$jurnal_cat4;
			    //$where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat4'";
		    }
			if($jurnal_cat5){
				$jurnal_array[]=$jurnal_cat5;
			    //$where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat5'";
		    }
			if(count($jurnal_array)){
				if(empty($where_date)){
				 $where_date .= " WHERE jurnal_cat  in(". implode(',',$jurnal_array).") ";
				}else{
				 $where_date .= " and jurnal_cat  in(". implode(',',$jurnal_array).") ";
				}
			}
			
	        if(!$sta ){
				$sta = ' service_payment="liqpay" or service_payment="portmone" or service_payment="admin"';
				
			}
			
			
			//die($sta) sandbox;
		  $status = '  and( l.status ="success" or l.status="PAYED" or l.status="CREATED")';
		
	  //----------------------------count-----------------------------------------------------------------
	  $count='SELECT  SUM( if(tabl2.price,tabl2.price,0) + if(tabl2.color_yellow,tabl2.color_yellow,0) + if(tabl2.urgently,tabl2.urgently,0)+ if(tabl2.show_competitor,tabl2.show_competitor,0)+ if(tabl2.update_date,tabl2.update_date,0) +if(tabl2.jurnal,tabl2.jurnal,0)) as price
			
			

			FROM (
				(
				 SELECT
					l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
					l.show_competitor, l.end_competitor, 
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM products_new AS prod
						INNER JOIN  payment_all AS l ON prod.product_new_id=l.ads_id and l.section_id =3'.$service_payment . $status.'
							

				)UNION(
				
				 SELECT 
				    l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM ads
						INNER JOIN  payment_all AS l ON ads.ads_id=l.ads_id and l.section_id =4 '.$service_payment .$status.'
					
						
			  )UNION(
				SELECT
				    l.ads_id,
					l.section_id,
				    l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM activity
						INNER JOIN  payment_all AS l ON  activity.activity_id=l.ads_id and l.section_id =5 '.$service_payment  .$status.'	
					
			  )UNION(
				
				SELECT
					 l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM labs AS lab
					INNER JOIN  payment_all AS l ON lab.lab_id=l.ads_id and l.section_id =7 '.$service_payment .$status.'
				
					
			  )UNION(
				SELECT
					 l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM realty 
						INNER JOIN  payment_all AS l ON realty.realty_id=l.ads_id and l.section_id =8 '.$service_payment  .$status.'
			  
						
			  )UNION(
				SELECT
					 l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM services
						INNER JOIN  payment_all AS l ON services.service_id=l.ads_id and l.section_id =9 '.$service_payment .$status.'
			  
						
			  )UNION(
				SELECT
					l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM vacancies
						INNER JOIN  payment_all AS l ON  vacancies.vacancy_id=l.ads_id and l.section_id =15 '.$service_payment .$status.'

			  )UNION(
			  
				SELECT
				    l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM demand
						INNER JOIN  payment_all AS l ON demand.demand_id=l.ads_id and l.section_id =11 '.$service_payment  .$status.'
			  )
			  

			) as tabl2 
			left JOIN  light_content AS l_c  ON  tabl2.ads_id = l_c.resource_id AND  tabl2.section_id =l_c.section_id 
			INNER JOIN  sections  ON tabl2.section_id =sections.section_id 
							'.$where_date.'';
	  
	    //----------------------------count end-----------------------------------------------------------------
			$query='SELECT  tabl.* ,
			us.name,us.contact_phones ,us.user_id,tabl.add_data, tabl.id, tabl.section_id,  tabl.resource_id,
			l_c.date_start,
			l_c.date_end ,
			sections .link ,
			/* (SELECT  SUM(price) FROM `payment_all` WHERE     '.$sta.'     ) as all_sum  */
			('.$count.'  ) as all_sum,
			color_yellow,
			urgently,
			show_competitor,
			end_competitor,
			
			update_date

			FROM (
				(
				 SELECT
					 l.id,
					 prod.product_new_id  AS resource_id,
					 prod.product_name as content,
					 prod.user_id ,
					  prod.product_name,
					 l.section_id,
					 l.service_payment,
					 l.price,
					 l.order_id,
					 l.color_yellow,
					 l.urgently,
					 l.show_competitor, 
					 l.end_competitor, 
					 l.date_add as add_data,
					 l.update_date,
					 l.jurnal,
					 l.jurnal_cat
					 FROM products_new AS prod
						INNER JOIN  payment_all AS l ON prod.product_new_id=l.ads_id and l.section_id =3'.$service_payment . $status.'
							

				)UNION(
				
				 SELECT 
				    l.id,
					ads.ads_id ,
					ads.product_name,
					ads.user_id,
					ads.product_name,
					l.section_id ,
					l.service_payment,
					l.price,
					l.order_id,
					l.color_yellow,
					l.urgently,
					l.show_competitor,
					l.end_competitor,
					l.date_add as add_data,					
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM ads
						INNER JOIN  payment_all AS l ON ads.ads_id=l.ads_id and l.section_id =4 '.$service_payment .$status.'
					
						
			  )UNION(
				SELECT
					l.id,
					activity.activity_id,
					activity.name,
					activity.user_id,
					activity.name,
					l.section_id,
					l.service_payment,
					l.price,
					l.order_id,
					NULLIF(l.color_yellow,0) AS color_yellow,
					NULLIF(l.urgently,0)AS urgently,
					NULLIF(l.show_competitor,  0) AS show_competitor,
					l.end_competitor,
					l.date_add as add_data,
					NULLIF(l.update_date,0)AS update_date,
					l.jurnal,
					l.jurnal_cat
					FROM activity
					INNER JOIN  payment_all AS l ON  activity.activity_id=l.ads_id and l.section_id =5 '.$service_payment  .$status.'	
					
			  )UNION(
				
				SELECT
					l.id,
					lab.lab_id,
					lab.content,
					lab.user_id,
					lab.name,
					l.section_id,
					l.service_payment,
					l.price,
					l.order_id,
					l.color_yellow,
					l.urgently,
					l.show_competitor,
					l.end_competitor,
					l.date_add as add_data,	
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM labs AS lab
					INNER JOIN  payment_all AS l ON lab.lab_id=l.ads_id and l.section_id =7 '.$service_payment .$status.'
				
					
			  )UNION(
				SELECT
					 l.id,
					 realty.realty_id,
					 realty.name,
					 realty.user_id,
					 realty.name,
					 l.section_id,
					 l.service_payment,
					 l.price,
					 l.order_id,
					 l.color_yellow,
					 l.urgently,
					 l.show_competitor,
					 l.end_competitor,
					 l.date_add as add_data,	
					 l.update_date,
					 l.jurnal,
					 l.jurnal_cat
					 FROM realty 
						INNER JOIN  payment_all AS l ON realty.realty_id=l.ads_id and l.section_id =8 '.$service_payment  .$status.'
			  
						
			  )UNION(
				SELECT
					 l.id,
					 services.service_id,
					 services.name,
					 services.user_id,
					 services.name,
					 l.section_id,
					 l.service_payment,
					 l.price,
					 l.order_id,
					 l.color_yellow,
					 l.urgently,
					 l.show_competitor, 
					 l.end_competitor, 
					 l.date_add as add_data,
					 l.update_date,
					 l.jurnal,
					 l.jurnal_cat
					 FROM services
						INNER JOIN  payment_all AS l ON services.service_id=l.ads_id and l.section_id =9 '.$service_payment .$status.'
			  
						
			  )UNION(
				SELECT
					l.id,
					vacancies.vacancy_id,
					vacancies.search_name,
					vacancies.user_id,
					vacancies.search_name,
					l.section_id,
					l.service_payment,
					l.price,
					l.order_id,
					l.color_yellow,
					l.urgently,
					l.show_competitor, 
					l.end_competitor,
					l.date_add as add_data,					
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM vacancies
					INNER JOIN  payment_all AS l ON  vacancies.vacancy_id=l.ads_id and l.section_id =15 '.$service_payment .$status.'

			  )UNION(
			  
				SELECT
					l.id,
					demand.demand_id,
					demand.name,
					demand.user_id,
					demand.name,
					l.section_id,
					l.service_payment,
					l.price,
					l.order_id,
					l.color_yellow,
					l.urgently,
					l.show_competitor, 
					l.end_competitor,
					l.date_add as add_data,	
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM demand
					INNER JOIN  payment_all AS l ON demand.demand_id=l.ads_id and l.section_id =11 '.$service_payment  .$status.'
			  )
			  

			) as tabl 
			left JOIN  users_info AS us  ON tabl.user_id= us.user_id
			left JOIN  light_content AS l_c  ON  tabl.resource_id = l_c.resource_id AND  tabl.section_id =l_c.section_id 
			INNER JOIN  sections  ON tabl.section_id =sections.section_id 
							'.$where_date.'ORDER BY tabl.id  DESC  -- LIMIT '.$start.' ,'.$limit.'

			';

	 $all=\DB::getAssocArray($query); 

	  return $all; 
	}
	
	
	
	
	public function getCountPayment(  ){
		
		     $date_start=Request::post('date_start');
			 $date_end=Request::post('date_end');
		    	    
		     $liqpay=Request::post('liqpay','int');
			 $portmone= Request::post('portmone','int');
			 $admin=Request::post('admin','int');
			 $archive=Request::post('archive','int');
			 $aktiv=Request::post('aktiv','int');
			 $date=DB::now(1);
			 $top_3=Request::post('top_3','int');
			 $user_id =Request::get('user');
			 $jurnal_cat1 =Request::post('jurnal_cat1');
			 $jurnal_cat2 =Request::post('jurnal_cat2');
			 $jurnal_cat3 =Request::post('jurnal_cat3');
			 $jurnal_cat4 =Request::post('jurnal_cat4');
			 $jurnal_cat5 =Request::post('jurnal_cat5');
			 $sta='';
			 $time_select='';
			 
		
			 if($date_start and $date_end){
				/*  $zero=' 00:00:00';
				 $date_start.=$zero;
				 $date_end.=$zero; */
			       $time_select= " and (l.date_add Between '$date_start' AND '$date_end' ) ";
				  
			 }elseif($date_start ){
				/*  $date_start.=$zero;
				$time_select= " and (l.date_add >'$date_start' ) "; */
			 }elseif($date_end ){
				/*  $date_end.=$zero;
				$time_select= " and (l.date_add <'$date_end') "; */
			 }
			 
			 
			 
			 
			 
			 
		
			$service_payment=''; 
			$where_date='';
			if($liqpay){
				$service_payment .= ' l.service_payment="liqpay" or';
				$sta='service_payment="liqpay" or';
			}
			
			if($portmone){
				$service_payment .= ' l.service_payment="portmone" or';
				$sta .=' service_payment="portmone" or ';
			}
			
			if($admin){
				//$service_payment .= ' l.service_payment="admin" or';
				//$sta .=' service_payment="admin" or';
			}
			
			if($service_payment){
				$service_payment=' and ('.trim($service_payment, 'or').')';
				$sta=' '.trim($sta, 'or').'';
			}else{
				
				$service_payment = ' and (service_payment="liqpay" or service_payment="portmone") ';
			}
			$service_payment= ($time_select .' '.$service_payment);
	    if($aktiv){
				$where_date .=' and l_c.date_start<= "'.$date.'" AND  l_c.date_end > "'.$date.'"' ;
					//var_dump($where_date);die;
		   }
	
		   if($archive){
				$where_date .=' and  l_c.date_end < "'.$date.'"' ;
		   }
		   
		   
		    
	   
		   if($archive and $aktiv)
		   {
		     $where_date='';
		   }
		   
		   
		    if($top_3){
				 $top_end = date('Y-m-d',( time() +((24*60*60)*3)  ));
				 $START_TOP=date('Y-m-d',( time()+ (24*60*60) ));
				 $where_date = " and (l_c.date_end  Between '$START_TOP' AND '$top_end' )" ;

			} 
			
		     if($user_id){ 
			    $where_date .= " WHERE  us.user_id = '$user_id' ";

		    }
			
		   $jurnal_array=array();
		   if($jurnal_cat1){
			    $jurnal_array[]=$jurnal_cat1;
			   // $where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat1'";
		    }
		   if($jurnal_cat2){
			    $jurnal_array[]=$jurnal_cat2;
			   // $where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat2'";
		    }
			if($jurnal_cat3){
				 $jurnal_array[]=$jurnal_cat3;
			    //$where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat3'";
		    }
			if($jurnal_cat4){
				$jurnal_array[]=$jurnal_cat4;
			    //$where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat4'";
		    }
			if($jurnal_cat5){
				$jurnal_array[]=$jurnal_cat5;
			    //$where_date .= " WHERE tabl.jurnal_cat  = '$jurnal_cat5'";
		    }
			if(count($jurnal_array)){
				if(empty($where_date)){
				 $where_date .= " WHERE jurnal_cat  in(". implode(',',$jurnal_array).") ";
				}else{
				 $where_date .= " and jurnal_cat  in(". implode(',',$jurnal_array).") ";
				}
			}
			
		   
	        if(!$sta ){
				$sta = ' service_payment="liqpay" or service_payment="portmone" or service_payment="admin"';
				
			}
			
			
			
			
			
			//die($sta) sandbox;
		  $status = '  and( l.status ="success" or  l.status="success" or l.status="PAYED" or l.status="CREATED")';
		
	  //----------------------------count-----------------------------------------------------------------
	  $count='SELECT  SUM( if(tabl2.price,tabl2.price,0) + if(tabl2.color_yellow,tabl2.color_yellow,0) + if(tabl2.urgently,tabl2.urgently,0)+ if(tabl2.show_competitor,tabl2.show_competitor,0)+ if(tabl2.update_date,tabl2.update_date,0) + if(tabl2.jurnal,tabl2.jurnal,0)) as price
			
			

			FROM (
				(
				 SELECT
					l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
					l.show_competitor,
					l.end_competitor, 
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM products_new AS prod
						INNER JOIN  payment_all AS l ON prod.product_new_id=l.ads_id and l.section_id =3'.$service_payment . $status.'
							

				)UNION(
				
				 SELECT 
				    l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, 
					l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM ads
						INNER JOIN  payment_all AS l ON ads.ads_id=l.ads_id and l.section_id =4 '.$service_payment .$status.'
					
						
			  )UNION(
				SELECT
				    l.ads_id,
					l.section_id,
				    l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor,
					l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM activity
						INNER JOIN  payment_all AS l ON  activity.activity_id=l.ads_id and l.section_id =5 '.$service_payment  .$status.'	
					
			  )UNION(
				
				SELECT
					 l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, 
					l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM labs AS lab
					INNER JOIN  payment_all AS l ON lab.lab_id=l.ads_id and l.section_id =7 '.$service_payment .$status.'
				
					
			  )UNION(
				SELECT
					 l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor,
					l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM realty 
						INNER JOIN  payment_all AS l ON realty.realty_id=l.ads_id and l.section_id =8 '.$service_payment  .$status.'
			  
						
			  )UNION(
				SELECT
					 l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, 
					l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM services
						INNER JOIN  payment_all AS l ON services.service_id=l.ads_id and l.section_id =9 '.$service_payment .$status.'
			  
						
			  )UNION(
				SELECT
					 l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, 
					l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM vacancies
						INNER JOIN  payment_all AS l ON  vacancies.vacancy_id=l.ads_id and l.section_id =15 '.$service_payment .$status.'

			  )UNION(
			  
				SELECT
				    l.ads_id,
					l.section_id,
					l.price,
					l.color_yellow,
					l.urgently,
			        l.show_competitor, 
					l.end_competitor, 
			        l.update_date,
					l.jurnal,
					l.jurnal_cat
					 FROM demand
						INNER JOIN  payment_all AS l ON demand.demand_id=l.ads_id and l.section_id =11 '.$service_payment  .$status.'
			  )
			  

			) as tabl2 
			left JOIN  light_content AS l_c  ON  tabl2.ads_id = l_c.resource_id AND  tabl2.section_id =l_c.section_id 
			inner JOIN  sections  ON tabl2.section_id =sections.section_id 
							'.$where_date.'';
	  
	    //----------------------------count end-----------------------------------------------------------------
			$query='SELECT  tabl.* ,
			us.name,us.contact_phones , us.user_id,
			l_c.date_start,
			l_c.date_end ,
			sections .link ,
			/* (SELECT  SUM(price) FROM `payment_all` WHERE     '.$sta.'     ) as all_sum  */
			('.$count.'  ) as all_sum,
			color_yellow,
			urgently,
			show_competitor,
			end_competitor,
			update_date

			FROM (
				(
				 SELECT
					 l.id,
					 prod.product_new_id  AS resource_id,
					 prod.product_name as content,
					 prod.user_id ,
					  prod.product_name,
					 l.section_id,
					 l.service_payment,
					 l.price,
					 l.order_id,
					 l.color_yellow,
					 l.urgently,
					 l.show_competitor, 
					 l.end_competitor, 
					 l.update_date,
					 l.jurnal,
					 l.jurnal_cat
					 FROM products_new AS prod
						INNER JOIN  payment_all AS l ON prod.product_new_id=l.ads_id and l.section_id =3'.$service_payment . $status.'
							

				)UNION(
				
				 SELECT 
				    l.id,
					ads.ads_id ,
					ads.product_name,
					ads.user_id,
					ads.product_name,
					l.section_id ,
					l.service_payment,
					l.price,
					l.order_id,
					l.color_yellow,
					l.urgently,
					l.show_competitor, 
					l.end_competitor, 
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM ads
						INNER JOIN  payment_all AS l ON ads.ads_id=l.ads_id and l.section_id =4 '.$service_payment .$status.'
					
						
			  )UNION(
				SELECT
					l.id,
					activity.activity_id,
					activity.name,
					activity.user_id,
					activity.name,
					l.section_id,
					l.service_payment,
					l.price,
					l.order_id,
					NULLIF(l.color_yellow,0) AS color_yellow,
					NULLIF(l.urgently,0)AS urgently,
					NULLIF(l.show_competitor, 0) AS show_competitor,
					l.end_competitor,
					NULLIF(l.update_date,0)AS update_date,
					l.jurnal,
					l.jurnal_cat
					FROM activity
					INNER JOIN  payment_all AS l ON  activity.activity_id=l.ads_id and l.section_id =5 '.$service_payment  .$status.'	
					
			  )UNION(
				
				SELECT
					l.id,
					lab.lab_id,
					lab.content,
					lab.user_id,
					lab.name,
					l.section_id,
					l.service_payment,
					l.price,
					l.order_id,
					l.color_yellow,
					l.urgently,
					l.show_competitor, 
					l.end_competitor, 
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM labs AS lab
					INNER JOIN  payment_all AS l ON lab.lab_id=l.ads_id and l.section_id =7 '.$service_payment .$status.'
				
					
			  )UNION(
				SELECT
					 l.id,
					 realty.realty_id,
					 realty.name,
					 realty.user_id,
					 realty.name,
					 l.section_id,
					 l.service_payment,
					 l.price,
					 l.order_id,
					 l.color_yellow,
					 l.urgently,
					 l.show_competitor, 
					 l.end_competitor, 
					 l.update_date,
					 l.jurnal,
					 l.jurnal_cat
					 FROM realty 
						INNER JOIN  payment_all AS l ON realty.realty_id=l.ads_id and l.section_id =8 '.$service_payment  .$status.'
			  
						
			  )UNION(
				SELECT
					 l.id,
					 services.service_id,
					 services.name,
					 services.user_id,
					 services.name,
					 l.section_id,
					 l.service_payment,
					 l.price,
					 l.order_id,
					 l.color_yellow,
					 l.urgently,
					 l.show_competitor,
					 l.end_competitor, 
					 l.update_date,
					 l.jurnal,
					 l.jurnal_cat
					 FROM services
						INNER JOIN  payment_all AS l ON services.service_id=l.ads_id and l.section_id =9 '.$service_payment .$status.'
			  
						
			  )UNION(
				SELECT
					l.id,
					vacancies.vacancy_id,
					vacancies.search_name,
					vacancies.user_id,
					vacancies.search_name,
					l.section_id,
					l.service_payment,
					l.price,
					l.order_id,
					l.color_yellow,
					l.urgently,
					l.show_competitor,
					l.end_competitor, 
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM vacancies
					INNER JOIN  payment_all AS l ON  vacancies.vacancy_id=l.ads_id and l.section_id =15 '.$service_payment .$status.'

			  )UNION(
			  
				SELECT
					l.id,
					demand.demand_id,
					demand.name,
					demand.user_id,
					demand.name,
					l.section_id,
					l.service_payment,
					l.price,
					l.order_id,
					l.color_yellow,
					l.urgently,
					l.show_competitor, 
					l.end_competitor, 
					l.update_date,
					l.jurnal,
					l.jurnal_cat
					FROM demand
					INNER JOIN  payment_all AS l ON demand.demand_id=l.ads_id and l.section_id =11 '.$service_payment  .$status.'
			  )
			  

			) as tabl 
			left JOIN  users_info AS us  ON tabl.user_id= us.user_id
			left JOIN  light_content AS l_c  ON  tabl.resource_id = l_c.resource_id AND  tabl.section_id =l_c.section_id 
			INNER JOIN  sections  ON tabl.section_id =sections.section_id 
							'.$where_date.' ORDER BY tabl.id  DESC  

			';
//Site::d($query);
	 $all=\DB::getAssocArray($query); 
//Site::d($all,1);
	  return $all; 
	}
	
  
	
}

?>