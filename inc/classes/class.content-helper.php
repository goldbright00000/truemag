<?php
class CT_ContentHelper{
	/* 
	 * Get related posts of a post based on conditions
	 *
	 * $posttypes: post types
	 * $tags: tag slug
	 * $postformat : post format
	 */
	function tm_get_related_posts($posttypes, $tags, $postformat, $count, $orderby, $args = array()){
		$args = '';
		$cat = '';
		if(ot_get_option('related_video_by')){
			$cat = $tags;
			$tags = '';
		}
		if($posttypes==''){$posttypes='post';}
		global $post;
		if($postformat=='video'){
		$args = array(
			'post_type' => $posttypes,
			'posts_per_page' => $count,
			'post_status' => 'publish',
			'post__not_in' =>  array(get_the_ID($post)),
			'ignore_sticky_posts' => 1,
			'orderby' => $orderby,
			'tag' => $tags,
			'cat' => $cat,
			'tax_query' => array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-video',
			))
		);
		}
		else if($postformat=='standard')
		{
			$args = array(
			'post_type' => $posttypes,
			'posts_per_page' => $count,
			'post_status' => 'publish',
			'post__not_in' =>  array(get_the_ID($post)),
			'ignore_sticky_posts' => 1,
			'orderby' => $orderby,
			'tag' => $tags,
			'cat' => $cat,
			'tax_query' => array(
			array(
				'taxonomy' => 'post_format',
				'field' => 'slug',
				'terms' => 'post-format-video',
				'operator' => 'NOT IN'
			)),
		);
		}else {
			$args = array(
			'post_type' => $posttypes,
			'posts_per_page' => $count,
			'post_status' => 'publish',
			'post__not_in' =>  array(get_the_ID($post)),
			'orderby' => $orderby,
			'tag' => $tags,
			'cat' => $cat,
			'ignore_sticky_posts' => 1
		);	
		}
		
		$query = new WP_Query($args);
		
		return $query;
	}
	/* 
	 * Get item for trending, popular
	 * $conditions : most by :view, comment, likes, latest
	 * $number : Number of post
	 * $ids : List id
	 *
	 *
	 */
	function tm_get_popular_posts($conditions, $tags, $number, $ids,$sort_by, $categories, $args = array(), $themes_pur, $postformats=false,$timerange=false,$paged = false, $type = ''){
		if($postformats){
			$postformats = explode(',',$postformats);
			if(in_array('post-format-standard', $postformats)){
				$all_format = array('post-format-quote','post-format-audio','post-format-gallery','post-format-image','post-format-link','post-format-video');
				$not_in = array_diff($all_format, $postformats);
				$format_query =	array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => $not_in,
						'operator' => 'NOT IN',
					)
				);
			}else{
				$format_query =	array(
					array(
						'taxonomy' => 'post_format',
						'field' => 'slug',
						'terms' => $postformats,
						'operator' => 'IN',
					)
				);
			}
		}
        
        if(isset($timerange) && $timerange != '' && $ids == ''){
			if($conditions == 'likes'){
				global $wpdb;
				if($timerange == 'day'){$time_range = '1';}
				else if($timerange=='week'){$time_range='7';}
				else if($timerange=='month'){$time_range='1m';}
				else if($timerange=='year'){$time_range='1y';}
				$order_by = 'ORDER BY like_count DESC, post_title';
				$limit = $where ='';
				if($number > 0) {
					$limit = "LIMIT " . $number;
				}
							
				$show_excluded_posts = get_option('wti_like_post_show_on_widget');
				$excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
				
				if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
					$where .= "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
				}
				
				if($timerange != 'all') {
					if(function_exists('GetWtiLastDate')){
						$last_date = GetWtiLastDate($time_range);
					}
					$where .= " AND date_time >= '$last_date'";
				}
				
				$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";
				$query .= "WHERE L.post_id = P.ID AND post_status = 'publish' AND value > 0 $where GROUP BY post_id $order_by $limit";
				$posts = $wpdb->get_results($query);
				//$cates_ar = $cates;
				$p_data = array();
				//print_r($posts);
				if(count($posts) > 0) {
					foreach ($posts as $post) {
						$p_data[] = $post->post_id;
					}
				}
	
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'orderby'=> 'post__in',
					'order' => 'ASC',
					'post_status' => 'publish',
					'tag' => $tags,
					'post__in' =>  $p_data,
					'ignore_sticky_posts' => 1
				);

			}else if($conditions=='most_viewed' || $conditions==''){
                
                // check if BAW is installed and active
                if(function_exists('bawpvc_main')){
                    if($timerange=='day')
                    {
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_day-'.date("Ymd"),
                            'orderby' => 'meta_value_num',
                            'order' => 'DESC',
                            'post_status' => 'publish',
                        );				
                    }else
                    if($timerange=='week')
                    {
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_week-'.date("YW"),
                            'orderby' => 'meta_value_num',
                            'order' => '',
                            'post_status' => 'publish',
                        );				
                    }else
                    if($timerange=='month')
                    {
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_month-'.date("Ym"),
                            'orderby' => 'meta_value_num',
                            'order' => '',
                            'post_status' => 'publish',
                        );				
                    }else
                    if($timerange=='year')
                    {
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_year-'.date("Y"),
                            'orderby' => 'meta_value_num',
                            'order' => '',
                            'post_status' => 'publish',
                        );				
                    }else{
                        $args = array(
                            'post_type' => 'post',
                            'posts_per_page' => $number,
                            'meta_key' => '_count-views_all',
                            'orderby' => 'meta_value_num',
                            'order' => '',
                            'post_status' => 'publish',
                        );
                    }
                } elseif(function_exists('get_tptn_post_count_only')) {
                    $ids = '';
                    
                    $offset = 0;
                    $posts_per_page = get_option('posts_per_page');
                    
                    if($paged && $paged > 0) $offset = ($paged - 1) * $posts_per_page;

                    if($timerange == 'day')
                    {
                        $args2 = array(
                            'daily' => 1,
                            'daily_range' => 1,
                            'post_types' =>'post',
                            'limit' => $number,
                            'ofset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                        
                    }elseif($timerange == 'week'){
                        $args2 = array(
                            'daily' => 1,
                            'daily_range' => 7,
                            'post_types' =>'post',
                            'limit' => $number,
                            'offset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                    }elseif($timerange=='month'){
                        $args2 = array(
                            'daily' => 1,
                            'daily_range' => 30,
                            'post_types' =>'post',
                            'limit' => $number,
                            'offset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                    }elseif($timerange == 'year'){
                        $args2 = array(
                            'daily' => 1,
                            'daily_range' => 365,
                            'post_types' =>'post',
                            'limit' => $number,
                            'offset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                        
                    }else{
                        $args2 = array(
                            'daily' => 0,
                            'post_types' =>'post',
                            'limit' => $number,
                            'offset' => $offset
                        );
                        $ids = cactus_get_tptn_pop_posts($args2);
                    }
                    
                    $args = array_merge($args, array(
                        'post__in'=> $ids,
                        'orderby'=> 'post__in'
                    ));
                    
                }
			}else if($conditions == 'most_comments'){
				if($timerange == 'all'){	
					$args = array(
						'post_type' => 'post',
						'posts_per_page' => $number,
						'orderby' => 'comment_count',
						'order' => $sort_by,
						'post_status' => 'publish',
						'tag' => $tags
					);
				}else{
					if($timerange=='day'){	
						$some_comments = get_comments( array(
							'date_query' => array(
								array(
									'after' => '1 day ago',
								),
							),
						) );
					}else
					if($timerange=='week'){
						$some_comments = get_comments( array(
							'date_query' => array(
								array(
									'after' => '1 week ago',
								),
							),
						) );
					}else
					if($timerange=='month'){
						$some_comments = get_comments( array(
							'date_query' => array(
								array(
									'after' => '1 month ago',
								),
							),
						) );	
					}else
					if($timerange=='year'){	
						$some_comments = get_comments( array(
							'date_query' => array(
								array(
									'after' => '1 year ago',
								),
							),
						) );	
					}				
					$arr_id= array();
					foreach($some_comments as $comment){
						$arr_id[] = $comment->comment_post_ID;
					}
					$arr_id = array_unique($arr_id, SORT_REGULAR);

					$args = array(
						'post_type' => 'post',
						'posts_per_page' => $number,
						'order' => $sort_by,
						'post_status' => 'publish',
						'post__in' =>  $arr_id,
						'ignore_sticky_posts' => 1,
					);
				}
			}else{
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'order' => $sort_by,
					'post_status' => 'publish',
					'tag' => $tags,
					'ignore_sticky_posts' => true);
                    
					if($timerange != 'all'){
						if($timerange=='week'){$number_day ='7';
						}elseif($timerange=='day'){$number_day ='1';}
						elseif($timerange=='month'){$number_day ='30';}
						elseif($timerange=='year'){$number_day='365';}
						$limit_date =  date('Y-m-d', strtotime('-' . $number_day . ' day'));
						$args['date_query'] = array(
								'after'         => $limit_date
						);
					}
			}
			if($postformats){
				$args['tax_query'] =  $format_query;
			}elseif($themes_pur!='0'){
			$args['tax_query'] =  array(
				array(
					'taxonomy' => 'post_format',
					'field' => 'slug',
					'terms' => 'post-format-video',
				));
			}	
			if(!is_array($categories)) {
				if(isset($categories)){
					$cats = explode(",",$categories);
					if(is_numeric($cats[0])){
						$args['category__in'] = $cats;
					}else{			 
						$args['category_name'] = $categories;
					}
				}
			}else if(count($categories) > 0){
				$args += array('category__in' => $categories);
			}
			if($paged){$args['paged'] = $paged;}

			$query = new WP_Query($args);
			return $query;
		}else{
			if($conditions == 'most_viewed' && $ids == ''){
                
                if(function_exists('bawpvc_main')){
				  $args = array(
					  'post_type' => 'post',
					  'posts_per_page' => $number,
					  'meta_key' => '_count-views_all',
					  'orderby' => 'meta_value_num',
					  'order' => $sort_by,
					  'post_status' => 'publish',
					  'tag' => $tags,
					  'ignore_sticky_posts' => 1,
						'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => 'post-format-audio',
							'operator' => 'NOT IN'
						)),
					  
				  );
                } elseif(function_exists('get_tptn_post_count')) {
                    
                    $ids = '';
                    
                    $offset = 0;
                    $posts_per_page = get_option('posts_per_page');
                    
                    if($paged && $paged > 0) $offset = ($paged - 1) * $posts_per_page;
                        
                    $args2 = array(
                        'daily' => 0,
                        'post_types' => 'post',
                        'limit' => $number,
                        'offset' => $offset
                    );
                    
                    $ids = cactus_get_tptn_pop_posts($args2);
                    
                    $args = array_merge($args, array(
                        'post__in'=> $ids,
                        'orderby'=> 'post__in'
                    ));
                    
                    
                }
                
                if($postformats){
                    $args['tax_query'] =  $format_query;
                } elseif($themes_pur!='0'){
                    $args['tax_query'] =  array(
                        array(
                            'taxonomy' => 'post_format',
                            'field' => 'slug',
                            'terms' => 'post-format-video',
                        ));
                }			
			}elseif($conditions == 'most_comments'  && $ids==''){
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'orderby' => 'comment_count',
					'order' => $sort_by,
					'post_status' => 'publish',
					'tag' => $tags,
					'ignore_sticky_posts' => 1,
						'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => 'post-format-audio',
							'operator' => 'NOT IN'
						)),
					);
					if($postformats){
						$args['tax_query'] =  $format_query;
					}elseif($themes_pur!='0'){
					$args['tax_query'] =  array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => 'post-format-video',
						));
					}			
	
			}elseif($conditions == 'high_rated'  && $ids==''){
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'meta_key' => '_count-views_all',
					'orderby' => 'meta_value_num',
					'order' => $sort_by,
					'post_status' => 'publish',
					'tag' => $tags,
					'ignore_sticky_posts' => 1,
					);
					if($postformats){
						$args['tax_query'] =  $format_query;
					}elseif($themes_pur!='0'){
					$args['tax_query'] =  array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => 'post-format-video',
						));
					}			
	
			}elseif($conditions=='playlist'){
				$ids = explode(",", $ids);
				$gc = array();
				$dem=0;
				foreach ( $ids as $grid_cat ) {
					$dem++;
					array_push($gc, $grid_cat);
				}
				$args = array(
					'post_type' => 'post',
					'post__in' =>  $gc,
					'posts_per_page' => $number,
					'orderby' => 'post__in',
					'order' => $sort_by,
					'ignore_sticky_posts' => 1,
					'tax_query' => array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => 'post-format-video',
					)),
				);
			}elseif($ids!=''){
				$ids = explode(",", $ids);
				$gc = array();
				$dem=0;
				foreach ( $ids as $grid_cat ) {
					$dem++;
					array_push($gc, $grid_cat);
				}
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'orderby' => 'post__in',
					'post_status' => 'publish',
					'tag' => $tags,
					'post__in' =>  $gc,
					'ignore_sticky_posts' => 1,
				);
			}elseif($ids=='' && $conditions=='latest'){
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'order' => $sort_by,
					'post_status' => 'publish',
					'tag' => $tags,
					'ignore_sticky_posts' => 1,
					);
					if($postformats){
						$args['tax_query'] =  $format_query;
					}elseif($themes_pur!='0'){
					$args['tax_query'] =  array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => 'post-format-video',
						));
					}			
	
			}elseif($ids=='' && $conditions=='title'){
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'order' => $sort_by,
					'orderby' => 'title',
					'post_status' => 'publish',
					'tag' => $tags,
					'ignore_sticky_posts' => 1,
					);
					if($postformats){
						$args['tax_query'] =  $format_query;
					}elseif($themes_pur!='0'){
					$args['tax_query'] =  array(
						array(
							'taxonomy' => 'post_format',
							'field' => 'slug',
							'terms' => 'post-format-video',
						));
					}			
	
			}elseif($ids== '' && $conditions=='random'){
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					'order' => $sort_by,
					'orderby' => 'rand',
					'post_status' => 'publish',
					'tag' => $tags,
					'ignore_sticky_posts' => 1,
	
				);
				if($postformats){
					$args['tax_query'] =  $format_query;
				}
			}elseif($ids=='' && $conditions=='likes'){
				//echo 'ok';
				global $wpdb;	
				$show_count = 1 ;
				$time_range = 'all';
				//$show_type = $instance['show_type'];
				$order_by = 'ORDER BY like_count DESC, post_title';
				$show_excluded_posts = get_option('wti_like_post_show_on_widget');
				$excluded_post_ids = explode(',', get_option('wti_like_post_excluded_posts'));
				
				if(!$show_excluded_posts && count($excluded_post_ids) > 0) {
					$where = "AND post_id NOT IN (" . get_option('wti_like_post_excluded_posts') . ")";
				}
				$query = "SELECT post_id, SUM(value) AS like_count, post_title FROM `{$wpdb->prefix}wti_like_post` L, {$wpdb->prefix}posts P ";
				$query .= "WHERE L.post_id = P.ID AND post_status = 'publish' AND value > -1 $where GROUP BY post_id $order_by";
				$posts = $wpdb->get_results($query);
				$cates_ar = $cates;
				$p_data = array();
				//print_r($posts);
				if(count($posts) > 0) {
					foreach ($posts as $post) {
						$p_data[] = $post->post_id;
					}
				}
				//$p_data = array_reverse($p_data);
				//print_r($p_data);
				$args = array(
					'post_type' => 'post',
					'posts_per_page' => $number,
					//'order' => $sort_by,
					'orderby'=> 'post__in',
					'order' => 'ASC',
					'post_status' => 'publish',
					'tag' => $tags,
					'post__in' =>  $p_data,
					'ignore_sticky_posts' => 1,
				);
			}
			if(!is_array($categories)) {
				if(isset($categories)){
					$cats = explode(",",$categories);
					if(count ($cats)==1 && is_numeric($cats)){
						$args += array('cat' => $categories);
					}else if(is_numeric($cats[0])){
						//$args += array('category__in' => $cats);
						$args['category__in'] = $cats;
					}else{			 
						$args['category_name'] = $categories;
					}
				}
			}else if(count($categories) > 0){
				$args += array('category__in' => $categories);
			}
			if($paged){$args['paged'] = $paged;}
            
            
			$query = new WP_Query($args);
			
			return $query;
		}
	}


}

if(!function_exists('cactus_get_tptn_pop_posts')){
	/*
	$args = array(
		'daily' => true, -- false to get all
		'daily_range' => '', -- number date to get
		'limit' => '', -- number post to query
		'offset' => '', -- number of posts to ignore
		'post_types' => '',
	);
	*/
	function cactus_get_tptn_pop_posts( $args = array() ) {
		if(!function_exists('get_tptn_post_count_only')){
			return;
		}
		global $wpdb, $tptn_settings;
		if($tptn_settings==''){ $tptn_settings = array();}	
		// Initialise some variables
		if($tptn_settings)
		$fields = '';
		$where = '';
		$join = '';
		$groupby = '';
		$orderby = '';
		$limits = '';
	
		$defaults = array(
			'daily' => true,
			'strict_limit' => true,
			'posts_only' => false,
		);
	
		// Merge the $defaults array with the $tptn_settings array
		$defaults = array_merge( $defaults, $tptn_settings );
	
		// Parse incomming $args into an array and merge it with $defaults
		$args = wp_parse_args( $args, $defaults );
		if ( $args['daily'] ) {
			$table_name = $wpdb->base_prefix . 'top_ten_daily';
		} else {
			$table_name = $wpdb->base_prefix . 'top_ten';
		}
		
	
		$limit = $args['limit'] ? $args['limit'] : 0;
	
		// If post_types is empty or contains a query string then use parse_str else consider it comma-separated.
		if ( ! empty( $args['post_types'] ) && false === strpos( $args['post_types'], '=' ) ) {
			$post_types = explode( ',', $args['post_types'] );
		} else {
			parse_str( $args['post_types'], $post_types );	// Save post types in $post_types variable
		}
	
		// If post_types is empty or if we want all the post types
		if ( empty( $post_types ) || 'all' === $args['post_types'] ) {
			$post_types = get_post_types( array(
				'public'	=> true,
			) );
		}
	
		$blog_id = get_current_blog_id();
	
		$current_time = current_time( 'timestamp', 0 );
		$from_date = $current_time - ( $args['daily_range'] * DAY_IN_SECONDS );
		$from_date = gmdate( 'Y-m-d' , $from_date );
	
		/**
		 *
		 * We're going to create a mySQL query that is fully extendable which would look something like this:
		 * "SELECT $fields FROM $wpdb->posts $join WHERE 1=1 $where $groupby $orderby $limits"
		 */
	
		// Fields to return
		$fields[] = 'ID';
		$fields[] = 'postnumber';
		$fields[] = ( $args['daily'] ) ? 'SUM(cntaccess) as sumCount' : 'cntaccess as sumCount';
	
		$fields = implode( ', ', $fields );
	
		// Create the JOIN clause
		$join = " INNER JOIN {$wpdb->posts} ON postnumber=ID ";
	
		// Create the base WHERE clause
		$where .= $wpdb->prepare( ' AND blog_id = %d ', $blog_id );				// Posts need to be from the current blog only
		$where .= " AND $wpdb->posts.post_status = 'publish' ";					// Only show published posts
	
		if ( $args['daily'] ) {
			$where .= $wpdb->prepare( " AND dp_date >= '%s' ", $from_date );	// Only fetch posts that are tracked after this date
		}
	
		// Convert exclude post IDs string to array so it can be filtered
		$exclude_post_ids = explode( ',', $args['exclude_post_ids'] );
	
		/**
		 * Filter exclude post IDs array.
		 *
		 * @param array   $exclude_post_ids  Array of post IDs.
		 */
		$exclude_post_ids = apply_filters( 'tptn_exclude_post_ids', $exclude_post_ids );
	
		// Convert it back to string
		$exclude_post_ids = implode( ',', array_filter( $exclude_post_ids ) );
	
		if ( '' != $exclude_post_ids ) {
			$where .= " AND $wpdb->posts.ID NOT IN ({$exclude_post_ids}) ";
		}
		$where .= " AND $wpdb->posts.post_type IN ('" . join( "', '", $post_types ) . "') ";	// Array of post types
	
		// How old should the posts be?
		if ( $args['how_old'] ) {
			$where .= $wpdb->prepare( " AND $wpdb->posts.post_date > '%s' ", gmdate( 'Y-m-d H:m:s', $current_time - ( $args['how_old'] * DAY_IN_SECONDS ) ) );
		}
	
		// Create the base GROUP BY clause
		if ( $args['daily'] ) {
			$groupby = ' postnumber ';
		}
	
		// Create the base ORDER BY clause
		$orderby = ' sumCount DESC ';
	
		// Create the base LIMITS clause
		$limits .= $limit ? $wpdb->prepare( ' LIMIT %d ', $limit ): '';	
		$limits .= $limits ? $wpdb->prepare('OFFSET %d', $args['offset'] ? $args['offset'] : 0) : '';
	
		if ( ! empty( $groupby ) ) {
			$groupby = " GROUP BY {$groupby} ";
		}
		if ( ! empty( $orderby ) ) {
			$orderby = " ORDER BY {$orderby} ";
		}
	
		$sql = "SELECT DISTINCT $fields FROM {$table_name} $join WHERE 1=1 $where $groupby $orderby $limits";
		$results = $wpdb->get_results( $sql );
		$ids = array();
		foreach ( $results as $result ) {
			$ids[] = $result->ID;
		}
		return $ids;
	}
}

?>