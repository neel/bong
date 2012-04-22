bong.batch = {
	_batchPath: '/bong/batch.php/',
	_interval: 0,
	_handle: null,
	_queue: [],
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
	add: function(path, callback, config){
		if(!this.exists(path)){
			var req = {
				url: path,
				f:   callback,
				conf: config
			};
			this._queue.push(req);
		}
	},
	exists: function(path){
		for(var i=0;i<this._queue.length;++i){
			if(this._queue[i].url == path)
				return true;
		}
		return false;
	},
	pull: function(){
		var payload_queue = this._queue;
		var n = payload_queue.length;
		var payload_str = this._buildRequest();
		var hash = Crypto.MD5(payload_str, {asString: false});
		var url = this._batchPath+'?n='+n+'&hash='+hash;
		(function(loads, self){
			bong.href(url, {
				method: 'post',
				params: 'payload='+escape(payload_str)
			}).invoke(function(data){
				/**
				 * Keep a copy of payload object to refer once the response has been recieved.
	             * decode the response.
				 * invoke the callbacks.
				 */
				for(var i=0;i<loads.length;++i){
					var load = loads[i];
					for(var j=0;j<data.length;++j){
						if(data[j].url == load.url){
							load.f(base64_decode(data[i].res));
						}
					}
					if(load.conf && load.conf.loop){
						//alert('here');
						self._queue.push(load);
					}
				}
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
