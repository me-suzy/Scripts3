<?
class MakeDisplay {
	var $layout;
	var $title;
	var $banner;
	var $menu;
	var $body;
	var $copyright;
	var $stats;

	function MakeDisplay() {
	}

	function CheckTemp($files) {
		
		foreach($files as $key=>$val) {
			if (file_exists($val) )
				$this->$key = implode("", file($val));

			else 
				$this->$key = $val;
		}
	}

	function DisplayTemp() {
		$this->layout = str_replace("<%% TITLE %%>" , $this->title  , $this->layout);
		$this->layout = str_replace("<%% BANNER %%>" , $this->banner , $this->layout);
		$this->layout = str_replace("<%% MENU %%>" , $this->menu  , $this->layout);
		$this->layout = str_replace("<%% BODY %%>" , $this->body , $this->layout);
		$this->layout = str_replace("<%% STATS %%>" , $this->stats  , $this->layout);
		$this->layout = str_replace("<%% FOOTER %%>" , $this->footer  , $this->layout);
		$this->layout = str_replace("<%% COPYRIGHT %%>" , $this->copyright  , $this->layout);
		print eval('?>' . $this->layout);
	}
}
?>