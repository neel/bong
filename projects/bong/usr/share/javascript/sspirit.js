var runtime = {
	_semaphore: 0,
	Up: function(){
		this._semaphore++;
		if(this._semaphore > 4){
			this._semaphore = 4;
		}
		//if($('#spiritPropertiesApply').hidden())
			$('#spiritPropertiesApply').show();
	},
	Dn: function(){
		this._semaphore--;
		if(this._semaphore <= 0){
			this._semaphore = 0;
			//if($('#spiritPropertiesApply').shown())
				$('#spiritPropertiesApply').hide();
		}
	}
}
$(document).ready(function(){
	
});
