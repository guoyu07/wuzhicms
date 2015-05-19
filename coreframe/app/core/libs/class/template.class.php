<?php
// +----------------------------------------------------------------------
// | wuzhicms [ 五指互联网站内容管理系统 ]
// | Copyright (c) 2014-2015 http://www.wuzhicms.com All rights reserved.
// | Licensed ( http://www.wuzhicms.com/licenses/ )
// | Author: wangcanjia <phpip@qq.com>
// +----------------------------------------------------------------------
defined('IN_WZ') or exit('No direct script access allowed');
/**
 * 模版编译类
 */
final class WUZHI_template {
    public function __construct() {
        //$this->db = load_class('db');
    }

	public function cache_template($m, $template, $style = 'default') {
		$template_file = COREFRAME_ROOT.'templates/'.$style.'/'.$m.'/'.$template.'.html';
		if (! file_exists ( $template_file )) {
            $template_file = str_replace(COREFRAME_ROOT,'',$template_file);
            exit($template_file." is not exists!" );
		}

		$cache_path = CACHE_ROOT.'templates/'.$style.'/'.$m.'/';
	    if(!is_dir($cache_path)) {
			mkdir($cache_path, 0777, true);
	    }
		$cache_file = $cache_path.$template.'.php';
		$data = file_get_contents($template_file);
		$data = $this->template_parse($data);
		$templatelen = @file_put_contents( $cache_file, $data );

        if($templatelen==false) MSG(str_replace(CACHE_ROOT,'caches/',$cache_file).' is readonly!');
		return $templatelen;
	}

	public function cache_dir_template($dirs = '') {
        if(empty($dirs)) $dirs = COREFRAME_ROOT."templates";
		if(is_dir($dirs)) {
			$dirs = glob($dirs."/*");
			foreach($dirs as $_dir) {
				self::cache_dir_template($_dir);
			}
		} else {
			$file_str = str_replace(COREFRAME_ROOT.'templates','',$dirs);
			if(substr($file_str,-5)!='.html') return true;
			$file_str = str_replace("\\",'/',$file_str);
			$file_str = ltrim($file_str,'/');
			$file_strs = explode('/',$file_str);
			$m = $template = $style = '';
			foreach($file_strs as $_i=>$_sr) {
				if($_i>0 && strpos($_sr,'.html')===false) {
					$m .= $_sr.'/';
				}
			}
			$m = rtrim($m,'/');
			$template = str_replace('.html','',array_pop($file_strs));
			$style = $file_strs[0];
			
			$this->cache_template($m, $template, $style);
		}
        return TRUE;
	}
	public function template_parse($template) {
        $template = preg_replace_callback("/\{block=([0-9]+)\}/i", "self::block", $template);
		$template = preg_replace ( "/\{T\s+(.+)\}/", "<?php if(!isset(\$siteconfigs)) \$siteconfigs=get_cache('siteconfigs'); include T(\\1); ?>", $template );
		$template = preg_replace ( "/\{if\s+(.+?)\}/", "<?php if(\\1) { ?>", $template );
		$template = preg_replace ( "/\{else\}/", "<?php } else { ?>", $template );
		$template = preg_replace ( "/\{elseif\s+(.+?)\}/", "<?php } elseif (\\1) { ?>", $template );
		$template = preg_replace ( "/\{\/if\}/", "<?php } ?>", $template );

		$template = preg_replace("/\{\+\+(.+?)\}/","<?php ++\\1; ?>",$template);
		$template = preg_replace("/\{\-\-(.+?)\}/","<?php ++\\1; ?>",$template);
		$template = preg_replace("/\{(.+?)\+\+\}/","<?php \\1++; ?>",$template);
		$template = preg_replace("/\{(.+?)\-\-\}/","<?php \\1--; ?>",$template);
        $template = preg_replace ( "/\{php\s+(.+)\}/", "<?php \\1?>", $template );
		$template = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\}/", "<?php \$n=1;if(is_array(\\1)) foreach(\\1 AS \\2) { ?>", $template );
		$template = preg_replace ( "/\{loop\s+(\S+)\s+(\S+)\s+(\S+)\}/", "<?php \$n=1; if(is_array(\\1)) foreach(\\1 AS \\2 => \\3) { ?>", $template );
		$template = preg_replace ( "/\{\/loop\}/", "<?php \$n++;}?>", $template );


        $template = preg_replace ( "/\{([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $template );
		$template = preg_replace ( "/\{\\$([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff:]*\(([^{}]*)\))\}/", "<?php echo \\1;?>", $template );
        $template = preg_replace ( "/\{(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)\}/", "<?php echo \\1;?>", $template );
        $template = preg_replace("/\{(\\$[a-zA-Z0-9_\[\]\'\"\$\x7f-\xff]+)\}/s",  "<?php echo \\1;?>",$template);
        $template = preg_replace ( "/\{([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)\}/s", "<?php echo \\1;?>", $template );

		$template = preg_replace_callback("/\{wz:(\w+)\s+([^}]+)\}/i", "self::syntax_parse", $template);
		$template = preg_replace_callback("/\{\/wz\}/i", "self::endof_syntax_parse", $template);

		$template = preg_replace("/\<\?(\s{1})/is", "<?php\\1", $template);
		$template = preg_replace("/\<\?\=(.+?)\?\>/is", "<?php echo \\1;?>", $template);
		$template = preg_replace_callback("/\{hook:(\w+?)(\s+(.+?))?\}/i", "self::hooktags", $template);


		$template = "<?php defined('IN_WZ') or exit('No direct script access allowed'); ?>" . $template;
		return $template;

	}
    private static function block($mat) {
        $block_config = @get_cache('block_'.$mat[1],'block');
        if(empty($block_config)) return '';
        $str = $block_config['code'];
        return $str;
    }
	private static function hooktags($mat) {
		$str = '<?php';
		$str .= "\r\n\$hook_class = load_class('hook');\r\n";
		$str .= "\$hook_class->run_hook('footer', $mat[2]);\r\n";
		$str .= "?>\r\n";
		return $str;
	}
	private static function syntax_parse($mat) {
        $m = $mat[1];
		preg_match_all("/([a-z]+)\=[\"]?([^\"]+)[\"]?/i", stripslashes($mat[2]), $matches, PREG_SET_ORDER);
		$arr = array('action','cache','page', 'pagesize', 'return', 'start');
		$tools = array('json', 'xml', 'block', 'sql');
		$datas = array();
		foreach ($matches as $v) {
			if(in_array($v[1], $arr)) {
				$$v[1] = $v[2];
				continue;
			}
			$datas[$v[1]] = $v[2];
		}
		$str = $str_datas = '';

		$pagesize = isset($pagesize) && intval($pagesize) ? intval($pagesize) : 20;

		$cache = isset($cache) && intval($cache) ? intval($cache) : 0;
		$return = isset($return) && trim($return) ? trim($return) : 'rs';
		if (!isset($urlrule)) $urlrule = '';
		if (!empty($cache) && !isset($page)) {
			
		}
		if (in_array($m,$tools)) {
			switch ($m) {
				case 'json':
						if (isset($datas['url']) && !empty($datas['url'])) {
							$str .= '$json = file_get_contents(\''.$datas['url'].'\');';
							$str .= '$'.$return.' = json_decode($json, true);';
						}
					break;
					
				case 'xml':
						
					break;
					
				case 'sql':
					$str .= '$'.$return.' = sql("'.$datas['sql'].'");'."\r\n\t";
					break;
			}
		} else {
			if (!isset($action) || empty($action)) return false;
			if (file_exists(COREFRAME_ROOT.'app/'.$m.'/libs/class/'.$m.'_template_parse.class.php')) {
				$str .= "if(!class_exists('".$m."_template_parse')) {\r\n";
				$str .= "\t".'$'.$m.'_template_parse = load_class("'.$m.'_template_parse", "'.$m.'");'."\r\n}\r\n".'if (method_exists($'.$m.'_template_parse, \''.$action.'\')) {'."\r\n\t";
                $datas['start'] = isset($start) ? intval($start) : 0;
                $datas['pagesize'] = isset($pagesize) ? intval($pagesize) : 20;

                $page = $datas['page'] = isset($page) ? $page : 0;

				$str .= '$'.$return.' = $'.$m.'_template_parse->'.$action.'('.self::arr_to_html($datas).');'."\r\n\t";
				//if ($page) {
                $str .= '$pages = $'.$m.'_template_parse->pages;';
                $str .= '$number = $'.$m.'_template_parse->number;';
				//}
				$str .= '}';
			} 
		}
		if (!empty($cache) && !$page) {
			//TODO
			$str .= 'if(!empty($'.$return.')){setcache($tag_cache_name, $'.$return.', \'tpl_data\');}';
			$str .= '}';
		}
		return "<"."?php if(defined('IN_ADMIN') && !defined('HTML')) {\r\n\techo \"<div class=\\\"visual_div\\\" pc_action=\\\"".$m."\\\" data=\\\"".$str_datas."\\\"><a href=\\\"javascript:void(0)\\\" class=\\\"visual_edit\\\">".L('edit')."</a>\";\r\n}\r\n".$str."?".">";
	}
	private static function endof_syntax_parse() {
		return "<?php if(defined('IN_ADMIN') && !defined('HTML')) {echo '</div>';}?>";
	}

	/**
	 * 转换数据为HTML代码
	 * @param array $data 数组
	 */
	private static function arr_to_html($data) {
		if (is_array($data)) {
			$str = 'array(';
			foreach ($data as $key=>$val) {
				if (is_array($val)) {
					$str .= "'$key'=>".self::arr_to_html($val).",";
				} else {
					if (strpos($val, '$')===0) {
						$str .= "'$key'=>$val,";
					} else {
						$str .= "'$key'=>'".p_addslashes($val)."',";
					}
				}
			}
			return $str.')';
		}
		return false;
	}
}
?>