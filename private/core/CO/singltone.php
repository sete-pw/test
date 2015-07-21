<?
	trait coSingltone{
		static private $ST = false;
		static public function RE(){
			if(false === (self::$ST instanceof self)){
				self::$ST = new self();
			}
			return self::$ST;
		}
	}