bong.batch = {
	_batchPath: '/bong/batch.php/',
	_interval: 0,
	_handle: null,
	_queue: [],
	_removal: [],
	_running: false,
	/**
	 * Build a HTTP Request to be sent to batch.php out of this._queue
	 * clears this._queue after making the string.
	 */
	_buildRequest: function(){
		var payload = {
			ts   : +new Date,
			rand : Math.floor(Math.random()*1000000000000),
			n    : this._queue.length,
			loads: this._queue
		}
		var payload_json = JSON.stringify(payload);
		this._queue = [];
		return payload_json;
	},
	/**
	 * add's an url to queue
	 * functor callback to be invoked with the response on 200 OK response
	 * url is removod from the queue unless {loop: true} in config
     */
	add: function(path, callback, config){
		console.log(path);
		if(!this.exists(path)){
			var req = {
				url: path,
				f:   callback,
				conf: config,
				checksum: null
			};
			this._queue.push(req);
		}else{
			var req = this.byPath(path)
			var f_current = req.f;
			var f_replace = function(data){
				f_current(data);
				callback(data);
			}
			req.f = f_replace;
		}
	},
	/**
	 * removes an url from the queue
	 * as bong.batch doesn't keep a persistant queue of requests, the queue is constantly cleared as not all requests have {loop: true}
	 * If that url is currently present in queue it removes unless keeps it in removal queue
	 * this.pull() checks in this._removal_list and if that url exists there it stops invoking callback and removes the url from this._removal_list
	 * So its not garunted that remove() will really remove that url from queue, rather it tries to.
	 * But its garunted that any callback assigned to that url will not be invoked by bong.batch.pull()
	 * However a request may be made even after remove()'ing but the response will get discarded
	 */
	remove: function(path){
		for(var i=0;i<this._queue.length;++i){
			if(this._queue[i].url == path)
				this._queue[i] = null;
				return true;
		}
		this._removal.push(path);
		return false;
	},
	/**
	 * removes by Regular Expression
	 * \see remove()
	 */
	removeByRegx: function(path_regx){
		var removed_n = 0;
		for(var i=0;i<this._queue.length;++i){
			var match = this._queue[i].url.match(path_regx);
			if(match && match.length == 1 && match[0] == this._queue[i].url){
				console.log("Matched {0}".format(this._queue[i].url));
				this._queue[i] = null;
				this._queue = this._queue.splice(i, 1);
				++removed_n;
			}
		}
		if(removed_n == 0){
			this._removal.push(path_regx);
			return null;
		}
		console.warn("Removed {0} urls".format(removed_n));
		return removed_n;
	},
	exists: function(path){
		if(this.byPath(path)){
			return true;
		}
		return false;
	},
	byPath: function(path){
		for(var i=0;i<this._queue.length;++i){
			if(this._queue[i].url == path)
				return this._queue[i];
		}
		return false;
	},
	pull: function(){
		if(this._running){
			console.warn('pull canceled');
			return;
		}
console.log('pulling');
		this._running = true;
		var payload_queue = this._queue;
		var n = payload_queue.length;
		var payload_str = this._buildRequest();
		//console.log(payload_str);
		var hash = Crypto.MD5(payload_str, {asString: false});
		/*If this Hash is not Checked on Serverside Crypto.MD5 dependancy can be removed*/
		var url = this._batchPath+'?n='+n+'&hash='+hash;
		(function(loads, self){
			bong.href(url, {
				method: 'post',
				params: 'payload='+escape(payload_str)
			}).invoke(function(data){
console.log('pulled');
				self._running = false;
				/**
				 * Keep a copy of payload object to refer once the response has been recieved.
	             * decode the response.
				 * invoke the callbacks.
				 */
				for(var i=0;i<loads.length;++i){
					var load = loads[i];
					for(var j=0;j<data.length;++j){
						if(data[j].url == load.url){
							var removal_requested = false;
							//{Search for existance of load.url in this._removal
							for(var removal_i=0;removal_i<bong.batch._removal.length;++removal_i){
								bong.batch._removal = bong.batch._removal.splice(removal_i, 1);
								if(bong.core.util.type(bong.batch._removal[removal_i]) == bong.core.util.type(new RegExp())){
									var match = load.url.match(bong.batch._removal[removal_i]);
									if(match && match.length == 1 && match[0] == load.url){
										bong.batch.removeByRegx(bong.batch._removal[removal_i]);
									}
								}else if(bong.batch._removal[removal_i] == load.url){
									bong.batch.remove(bong.batch._removal[removal_i]);
								}
								if(load.conf && load.conf.loop){
									load.conf.loop = false;
								}
								removal_requested = true;
								break;
							}
							//}
							if(!removal_requested){
								load.checksum = data[i].checksum
								//console.log(load.checksum);
								if(data[i].res.length > 0){
									var scope = {
										load: load,
										data: data[i]										
									}
									load.f.call(scope, base64_decode(data[i].res));
								}else{
									//console.warn('Skipped '+load.url);
								}
							}
						}
					}
					if(load.conf && load.conf.loop){
						//alert('here');
						self._queue.push(load);
					}
				}
				//bong.batch._removal = [];
			});
		}(payload_queue, this));
	},
	start: function(interval){
		this._interval = interval;
		if(!this._interval)
			this._interval = 3000;
		this._handle = setInterval(function(self){
			return function(){
				self.pull();
			}
		}(this), this._interval);
	},
	stop: function(){
		if(this._handle){
			clearInterval(this._handle);
		}
	},
	pause: function(){
		this.stop();
	},
	hitch: function(){
		this.pull();
	},
	load: function(){
		return this._queue.length;
	},
	cancel: function(){
		this._queue = [];
	}
}
bong.batch.start();
