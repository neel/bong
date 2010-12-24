//V3.01.A - http://www.openjs.com/scripts/jx/
jx = {
	//Create a xmlHttpRequest object - this is the constructor. 
	getHTTPObject : function() {
		var http = false;
		//Use IE's ActiveX items to load the file.
		if(typeof ActiveXObject != 'undefined') {
			try {http = new ActiveXObject("Msxml2.XMLHTTP");}
			catch (e) {
				try {http = new ActiveXObject("Microsoft.XMLHTTP");}
				catch (E) {http = false;}
			}
		//If ActiveX is not available, use the XMLHttpRequest of Firefox/Mozilla etc. to load the document.
		} else if (window.XMLHttpRequest) {
			try {http = new XMLHttpRequest();}
			catch (e) {http = false;}
		}
		return http;
	},
	bind : function(user_options) {
		var opt = {
			'url':'', 			//URL to be loaded
			'onSuccess':false,	//Function that should be called at success
			'onError':false,	//Function that should be called at error
			'format':"text",	//Return type - could be 'xml','json' or 'text'
			'method':"GET",		//GET or POST
			'update':"",		//The id of the element where the resulting data should be shown. 
			'loading':"",		//The id of the loading indicator. This will be set to display:block when the url is loading and to display:none when the data has finished loading.
			'loadingIndicator':"" //HTML that would be inserted into the document once the url starts loading and removed when the data has finished loading. This will be inserted into a div with class name 'loading-indicator' and will be placed at 'top:0px;left:0px;'
		}
		for(var key in opt) {
			if(user_options[key]) {//If the user given options contain any valid option, ...
				opt[key] = user_options[key];// ..that option will be put in the opt array.
			}
		}
		
		if(!opt.url) return; //Return if a url is not provided

		var div = false;
		if(opt.loadingIndicator) { //Show a loading indicator from the given HTML
			div = document.createElement("div");
			div.setAttribute("style","position:absolute;top:0px;left:0px;");
			div.setAttribute("class","loading-indicator");
			div.innerHTML = opt.loadingIndicator;
			document.getElementsByTagName("body")[0].appendChild(div);
			this.opt.loadingIndicator=div;
		}
		if(opt.loading) document.getElementById(opt.loading).style.display="block"; //Show the given loading indicator.
		
		this.load(opt.url,function(data){
			if(opt.onSuccess) opt.onSuccess(data);
			if(opt.update) document.getElementById(opt.update).innerHTML = data;
			
			if(div) document.getElementsByTagName("body")[0].removeChild(div); //Remove the loading indicator
			if(opt.loading) document.getElementById(opt.loading).style.display="none"; //Hide the given loading indicator.
		},opt.format,opt.method, opt);
	},
	init : function() {return this.getHTTPObject();}
}
