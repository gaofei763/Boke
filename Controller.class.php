<?php

	class Controller{
		
		//跳转方法
		public function jump(){
			
			if($time == 0){
			
				header("Location:$url");
			}else{
			
				include CUR_VIEW_PATH."message.html";
			}
			die();
		}
		//引入工具类
		public function library($lib){
		
			include LIB_PATH."{$lib}.class.php";

		}
		//引入辅助函数方法
		public function helper($help){
			
			include HELPER_PATH."{$help}.class.php";
		}

		//设置get和post
		public function input($name, $defaultValue = "") {

			// php这里区分大小写，将两者都变为小写
		    $_GET = array_change_key_case ( $_GET, CASE_LOWER );
			$name = strtolower ( $name );
		    $v = isset ( $_GET [$name] ) ? $_GET [$name] : "";

			if ($v == ""){

				$_POST = array_change_key_case ( $_POST, CASE_LOWER );
			    $v = isset ( $_POST [$name] ) ?$_POST [$name] : "";
			}
			if ($v == ""){

		    	return $defaultValue;
			}
		    else
			{

				// 20141011 jc :  js_unescape($v)会引起 where ( col_subject like '%123%' ) 会变成 where ( col_subject like '%3%' )
				//$v =  js_unescape($v) ;
				$v = trim($v);
				return $v;
			}	 

		}

}