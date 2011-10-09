<?php
/**
 * \controller SessionList
 */
class SessionListAbstractor extends SpiritAbstractor implements StaticBound, SerializableXDO, ControllerFeeded, SessionedSpirit{

	/**
	 * Method Generated by Bong Admin panel
	 */			
	public function main(){
		/*TODO Not Implemented Yet*/
	}
	public function desc($id){
		ControllerTray::instance()->renderLayout = false;
		http::contentType('application/json');
		$this->data->id = $id;
		$this->data->info = new \ROM\BongUserData();
		$this->data->info->load($id.'.usr');
	}
}