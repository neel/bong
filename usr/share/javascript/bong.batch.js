bong.batch = {
	_batchPath: '/bong/batch.php/',
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
		var req = {
			url: path,
			f:   callback,
			conf: config
		};
		this._queue.push(req);
	},
	pull: function(){
		var payload_queue = this._queue;
		var n = payload_queue.length;
		var payload_str = this._buildRequest();
		var hash = Crypto.MD5(payload_str, {asString: false});
		var url = this._batchPath+'?n='+n+'&hash='+hash;
		(function(loads){
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
				}
			});
		}(payload_queue));
	}
}
