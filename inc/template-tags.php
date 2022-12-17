<?php
/**
 * Custom template tags.
 *
 * @category Theme
 * @package  Smarz_Lab
 * @author   Serena Piccioni <serena@piccioni.london>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://smarz-lab.com/
 */

if (! function_exists('Sl_Get_link') ) {
    /**
     * Set up the single link.
     *
     * @param array  $args     Link args.
     * @param string $link     Link url.
     * @param string $name     Link name.
     * @param int    $position Link position.
     *
     * @return string
     */
    function Sl_Get_link( $args, $link, $name, $position )
    {
        $return = $args['before'];
        $return .= sprintf(
            $args['link'],
            $link,
            $name,
            sprintf($args['name'], $name)
        );
        $return .= sprintf($args['position'], $position);

        return $return;
    }
}

if (! function_exists('Sl_breadcrumbs') ) {
    /**
     * Breadcrumbs.
     *
     * @return void
     */
    function Sl_breadcrumbs()
    {

        $args = array(
        'before'        => '<li class="breadcrumb-item" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">',
        'before_active' => '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope="" itemtype="https://schema.org/ListItem">',
        'link'          => '<a href="%1$s" title="%2$s" itemscope itemtype="https://schema.org/Thing" itemprop="item" itemid="%1$s">%3$s</a>',
        'active'        => '<span itemscope itemtype="https://schema.org/Thing" itemprop="name" itemid="%1$s">%2$s</span>',
        'name'          => '<span itemprop="name">%1$s</span>',
        'position'      => '<meta itemprop="position" content="%1$s">',
        'text'          => array(
        'home'     => 'Home',
        'category' => '%s',
        'search'   => 'Risultati della ricerca per: %s',
        'tag'      => 'Tag: %s',
        'author'   => 'Autore: %s',
        '404'      => 'Errore 404',
        'page'     => 'Pagina %s',
        'cpage'    => 'Pagina %s'
        )
        );

        global $post;
        $home_url  = home_url('/');
        $parent_id = $post->post_parent ?? 0;
        $title     = get_the_title();

        $home_link = Sl_Get_link($args, $home_url, $args['text']['home'], 1);

        if (! is_front_page() ) {

            $position = 0;
            echo '<ol class="breadcrumb" id="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';

            $position ++;
            echo $home_link;

            if (is_category() ) {
                $parents = get_ancestors(get_query_var('cat'), 'category');
                foreach ( array_reverse($parents) as $cat ) {
                    $position ++;
                    echo Sl_Get_link($args, get_category_link($cat), get_cat_name($cat), $position);
                }
                if (get_query_var('paged') ) {
                    $position ++;
                    echo Sl_Get_link($args, get_category_link(get_query_var('cat')), get_cat_name(get_query_var('cat')), $position);
                    echo $args['before'] . sprintf($args['text']['page'], get_query_var('paged'));

                } else {
                    $position ++;
                    echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['name'], sprintf($args['text']['category'], single_cat_title('', false)))) . sprintf($args['position'], $position);
                }
            } elseif (is_search() ) {
                if (get_query_var('paged') ) {

                    $position ++;
                    echo Sl_Get_link($args, $home_url . '?s=' . get_search_query(), sprintf($args['text']['search'], get_search_query()), $position);
                    echo $args['before'] . sprintf($args['text']['page'], get_query_var('paged'));

                } else {

                    $position ++;
                    echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['text']['search'], get_search_query())) . sprintf($args['position'], $position);


                }
            } elseif (is_year() ) {

                $position ++;
                echo $args['before_active'] . sprintf($args['active'], get_permalink(), get_the_time('Y')) . sprintf($args['position'], $position);


            } elseif (is_month() ) {

                $position ++;
                echo Sl_Get_link($args, get_year_link(get_the_time('Y')), get_the_time('Y'), $position);

                $position ++;
                echo $args['before_active'] . sprintf($args['active'], get_permalink(), get_the_time('F')) . sprintf($args['position'], $position);

            } elseif (is_day() ) {

                $position ++;
                echo Sl_Get_link($args, get_year_link(get_the_time('Y')), get_the_time('Y'), $position);

                $position ++;
                echo Sl_Get_link($args, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'), $position);


                $position ++;
                echo $args['before_active'] . sprintf($args['active'], get_permalink(), get_the_time('d')) . sprintf($args['position'], $position);

            } elseif (is_single() && ! is_attachment() ) {
                $post_type = get_post_type_object(get_post_type());
                if ($post_type && get_post_type() !== 'post' ) {
                    $position ++;
                    echo Sl_Get_link($args, get_post_type_archive_link($post_type->name), $post_type->labels->name, $position);
                    $position ++;
                    $args['before_active'] . sprintf($args['active'], get_permalink(), $title) . sprintf($args['position'], $position);

                } else {
                    $cat       = get_the_category();
                    $catID     = $cat[0]->cat_ID;
                    $parents   = array_reverse(get_ancestors($catID, 'category'));
                    $parents[] = $catID;

                    foreach ( $parents as $cat ) {
                        $position ++;
                        echo Sl_Get_link($args, get_category_link($cat), get_cat_name($cat), $position);
                    }

                    if (get_query_var('cpage') ) {
                        $position ++;
                        echo Sl_Get_link($args, get_permalink(), $title, $position);

                        $position ++;
                        echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['text']['cpage'], get_query_var('cpage'))) . sprintf($args['position'], $position);

                    } else {
                        $position ++;
                        echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['name'], $title)) . sprintf($args['position'], $position);

                    }
                }
            } elseif (is_post_type_archive() ) {
                $post_type = get_post_type_object(get_post_type());
                if ($post_type && get_query_var('paged') ) {

                    $position ++;
                    echo Sl_Get_link($args, get_post_type_archive_link($post_type->name), $post_type->label, $position);

                    $position ++;
                    echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['text']['page'], get_query_var('paged'))) . sprintf($args['position'], $position);
                } else {

                    $position ++;
                    echo $args['before_active'] . sprintf($args['active'], get_permalink(), $post_type->label) . sprintf($args['position'], $position);

                }

            } elseif (is_attachment() ) {
                $parent    = get_post($parent_id);
                $cat       = get_the_category($parent->ID);
                $catID     = $cat[0]->cat_ID;
                $parents   = array_reverse(get_ancestors($catID, 'category'));
                $parents[] = $catID;
                foreach ( $parents as $cat ) {
                    $position ++;
                    echo Sl_Get_link($args, get_category_link($cat), get_cat_name($cat), $position);
                }

                $position ++;
                echo Sl_Get_link($args, get_permalink($parent), $parent->post_title, $position);

                $position ++;
                echo $args['before_active'] . sprintf($args['active'], get_permalink(), $title) . sprintf($args['position'], $position);

            } elseif (! $parent_id && is_page() ) {
                $position ++;
                echo $args['before_active'] . sprintf($args['active'], get_permalink(), $title) . sprintf($args['position'], $position);


            } elseif ($parent_id && is_page() ) {
                $parents = get_post_ancestors(get_the_ID());
                foreach ( array_reverse($parents) as $pageID ) {
                    $position ++;
                    echo Sl_Get_link($args, get_page_link($pageID), get_the_title($pageID), $position);
                }

                $position ++;
                echo $args['before_active'] . sprintf($args['active'], get_permalink(), $title) . sprintf($args['position'], $position);

            } elseif (is_home() ) {
                $position ++;
                echo $args['before_active'] . sprintf($args['active'], get_permalink(get_queried_object_id()), get_the_title(get_queried_object_id())) . sprintf($args['position'], $position);

            } else if (is_tag() ) {
                if (get_query_var('paged') ) {
                    $position ++;
                    $tagID = get_query_var('tag_id');
                    echo Sl_Get_link($args, get_tag_link($tagID), single_tag_title('', false), $position);

                    $position ++;
                    echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['text']['page'], get_query_var('paged'))) . sprintf($args['position'], $position);
                } else {

                    $position ++;
                    echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['text']['tag'], single_tag_title('', false))) . sprintf($args['position'], $position);

                }
            } elseif (is_author() ) {
                $author = get_userdata(get_query_var('author'));
                if (get_query_var('paged') ) {

                    $position ++;
                    echo Sl_Get_link($args, get_author_posts_url($author->ID), sprintf($args['text']['author'], $author->display_name), $position);

                    $position ++;
                    echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['text']['page'], get_query_var('paged'))) . sprintf($args['position'], $position);

                } else {

                    $position ++;
                    echo $args['before_active'] . sprintf($args['active'], get_permalink(), sprintf($args['text']['author'], $author->display_name)) . sprintf($args['position'], $position);

                }
            } elseif (is_404() ) {

                $position ++;
                echo $args['before_active'] . sprintf($args['active'], get_permalink(), $args['text']['404']) . sprintf($args['position'], $position);

            } elseif (has_post_format() && ! is_singular() ) {

                echo get_post_format_string(get_post_format());
            }

            echo '</ol>';
        }
    }
}

if (! function_exists('Sl_Comment_form') ) {
    /**
     * Custom comments form.
     *
     * @param int $post_id The post ID.
     *
     * @return void
     */
    function Sl_Comment_form( $post_id = false )
    {

        $comments_args = array(
        'format'               => 'xhtml',
        'comment_notes_before' => '<p>' . __('Your email address will not be published.') . '</p>',
        'class_submit'         => 'btn btn-outline-dark rounded-0',
        'fields'               => array(
        'author' => '<div class="form-floating mb-3">
							<input placeholder="' . __('Author') . '" class="form-control rounded-0" type="text" id="author" name="author" required>
							<label for="author">' . __('Author') . ' (' . __('required') . ')</label>
						</div>',
        'email'  => '<div class="form-floating mb-3">
							<input placeholder="' . __('Email') . '" class="form-control rounded-0" type="email" id="email" name="email" required>
							<label for="email">' . __('Email') . ' (' . __('required') . ')</label>
						</div>',
        'url'    => '<div class="form-floating mb-3">
							<input placeholder="' . __('Url') . '" class="form-control rounded-0" type="url" id="url" name="url">
							<label for="url">' . __('Url') . '</label>
						</div>',
        ),
        'comment_field'        => '<div class="form-floating mb-3">
								<textarea placeholder="' . __('Comment') . '" class="form-control rounded-0" id="comment" name="comment" style="height: 150px" required></textarea>
								<label for="comment">' . __('Comment') . ' (' . __('required') . ')</label>
								</div>',
        );

        if ($post_id ) {
            comment_form($comments_args, $post_id);
        } else {
            comment_form($comments_args);
        }
    }
}

if (! function_exists('Sl_Get_Menu_items') ) {
    /**
     * Get a menu as array from location.
     *
     * @param string $theme_location The menu location.
     *
     * @return array
     */
    function Sl_Get_Menu_items( $theme_location )
    {

        $locations = get_nav_menu_locations();
        if (( $locations ) && isset($locations[ $theme_location ]) ) {

            $menu       = get_term($locations[ $theme_location ], 'nav_menu');
            $menu_items = wp_get_nav_menu_items($menu->term_id);
            $menu_list  = array();
            $bool       = false;

            $i = 0;
            foreach ( $menu_items as $menu_item ) {
                if ((int) $menu_item->menu_item_parent === 0 ) {

                    $parent     = $menu_item->ID;
                    $menu_array = array();
                    $y          = 0;

                    foreach ( $menu_items as $submenu ) {
                        if (isset($submenu) && (int) $submenu->menu_item_parent === (int) $parent ) {
                            $bool       = true;
                            $menu_array = Sl_Get_arr($submenu, $menu_array, $y);
                            $y ++;
                        }
                    }

                    $menu_list = Sl_Get_arr($menu_item, $menu_list, $i);

                    if ($bool === true && count($menu_array) > 0 ) {
                        $menu_list[ $i ]['submenu'] = $menu_array;
                    }
                    $i ++;
                }
            }
        } else {
            $menu_list[] = '';
        }

        return $menu_list;
    }

    /**
     * Set up the menu array.
     *
     * @param object $menu       The menu object.
     * @param array  $menu_array The menu array.
     * @param int    $i          The menu position.
     *
     * @return array
     */
    function Sl_Get_arr( $menu, array $menu_array, int $i ): array
    {

        $menu_array[ $i ]['url']     = $menu->url;
        $menu_array[ $i ]['title']   = $menu->title;
        $menu_array[ $i ]['target']  = ! empty($menu->target) ? ' target="' . $menu->target . '"' : '';
        $menu_array[ $i ]['classes'] = implode(' ', $menu->classes);

        return $menu_array;
    }
}

if (! function_exists('Sl_pagination') ) {
    /**
     * Pagination.
     *
     * @param object $wp_query The query.
     * @param int    $paged    The page.
     *
     * @return void
     */
    function Sl_pagination( $wp_query, $paged )
    {
        global $lang;

        $first    = 'Primo';
        $last     = 'Ultimo';
        $previous = 'Precedente';
        $next     = 'Successivo';

        if ('en' === $lang ) {
            $first    = 'First';
            $last     = 'Last';
            $previous = 'Previous';
            $next     = 'Next';
        }

        $return   = '';
        $max_page = $wp_query->max_num_pages;

        $pages_to_show         = 8;
        $pages_to_show_minus_1 = $pages_to_show - 1;
        $half_page_start       = floor($pages_to_show_minus_1 / 2);
        $half_page_end         = ceil($pages_to_show_minus_1 / 2);
        $start_page            = $paged - $half_page_start;

        if ($start_page <= 0 ) {
            $start_page = 1;
        }

        $end_page = $paged + $half_page_end;
        if (( $end_page - $start_page ) !== $pages_to_show_minus_1 ) {
            $end_page = $start_page + $pages_to_show_minus_1;
        }

        if ($end_page > $max_page ) {
            $start_page = $max_page - $pages_to_show_minus_1;
            $end_page   = $max_page;
        }

        if ($start_page <= 0 ) {
            $start_page = 1;
        }

        if ($max_page > 1 ) {

            $return  = '<nav class="mt-1 mb-5">' . "\n";
            $return .= '<ul class="pagination flex-wrap">' . "\n";

            if (1 < (int) $paged ) {
                $return .= '<li class="page-item">' . "\n";
                $return .= '<a href="' . esc_url(get_pagenum_link()) . '" class="page-link btn-50" title="' . $first . '">&laquo;</a>' . "\n";
                $return .= '</li>' . "\n";
            }

            $return .= '<li class="page-item">' . "\n";
            $return .= str_replace('<a href="', '<a class="page-link btn-50" title="' . $previous . '" href="', get_previous_posts_link('&lsaquo;'));
            $return .= '</li>' . "\n";

            if ((int) $start_page >= 2 && $pages_to_show < $max_page ) {
                $return .= '<li class="page-item">' . "\n";
                $return .= '<a href="' . esc_url(get_pagenum_link()) . '" class="page-link btn-50" title="1">1</a>' . "\n";
                $return .= '</li>' . "\n";
                $return .= '<li class="page-item active" aria-current="page">
					<span class="page-link dots">...<span class="visually-hidden">(current)</span></span>
				  </li>';
            }

            for ( $i = $start_page; $i <= $end_page; $i ++ ) {
                if ((int) $i === (int) $paged ) {
                    $return .= '<li class="page-item active" aria-current="page">
						<span class="page-link page-number page-numbers current btn-50">' . number_format_i18n($i) . ' <span class="visually-hidden">(current)</span></span>
					</li>';
                } else {
                    $return .= '<li class="page-item">' . "\n";
                    $return .= '<a href="' . esc_url(get_pagenum_link($i)) . '" class="page-link btn-50" title="' . number_format_i18n($i) . '">' . number_format_i18n($i) . '</a>';
                    $return .= '</li>' . "\n";
                }
            }

            if ((int) $end_page < $max_page ) {
                $return .= '<li class="page-item active" aria-current="page">
							<span class="page-link dots">...<span class="visually-hidden">(current)</span></span>
						  </li>';
                $return .= '<li class="page-item">' . "\n";
                $return .= '<a href="' . esc_url(get_pagenum_link($max_page)) . '" class="page-link btn-50" title="' . $max_page . '">' . $max_page . '</a>';
                $return .= '</li>' . "\n";
            }

            $return .= '<li class="page-item">' . "\n";
            $return .= str_replace('<a href="', '<a class="page-link btn-50" title="' . $next . '" href="', get_next_posts_link('&rsaquo;', $max_page));
            $return .= '</li>' . "\n";

            if ((int) $max_page > (int) $paged ) {
                $return .= '<li class="page-item">' . "\n";
                $return .= '<a href="' . esc_url(get_pagenum_link($max_page)) . '" class="page-link btn-50" title="' . $last . '">&raquo;</a>';
                $return .= '</li>' . "\n";
            }
            $return .= '</ul>' . "\n";
            $return .= '</nav>' . "\n";
        }

        echo $return;
    }
}

if (! function_exists('Sl_Get_The_terms') ) {

    /**
     * Function to return list of the terms.
     *
     * @param string $taxonomy The taxonomy.
     * @param bool   $cut      No idea.
     *
     * @return string Returns the list of elements.
     */
    function Sl_Get_The_terms( $taxonomy, $cut = false )
    {

        $all_terms = '';
        $terms     = get_the_terms(get_the_ID(), $taxonomy);

        if ($terms && ! is_wp_error($terms) ) {

            $term_links = array();

            foreach ( $terms as $term ) {
                $term_links[] = '<li class="d-inline"><a href="' . esc_attr(get_term_link($term->slug, $taxonomy)) . '">' . esc_html($term->name) . '</a></li>';
            }

            if ($cut ) {
                $term_links    = array();
                $key           = count($terms) - 1;
                $term_links[0] = '<li class="d-inline"><a href="' . esc_attr(get_term_link($terms[ $key ]->slug, $taxonomy)) . '">' . esc_html($terms[ $key ]->name) . '</a></li>';
            }

            $all_terms = implode('<span class="badge bg-white text-dark rounded-0 border-0 fw-bold">|</span> ', $term_links);
        }

        return $all_terms;

    }
}

if (! function_exists('Sl_Get_excerpt') ) {
    /**
     * Set up an excerpt from $content.
     *
     * @param int $length The excerpt length.
     *
     * @return string
     * @throws Exception
     */
    function Sl_Get_excerpt( int $length = 50 )
    {

        $content = get_the_content();

        $content = strip_shortcodes($content);
        $content = excerpt_remove_blocks($content);
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);
        $content = wp_trim_words($content, $length, '...');

        if ('' === trim($content) ) {
            $content = get_the_excerpt();
        }

        return $content;
    }
}
