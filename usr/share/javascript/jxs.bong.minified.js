//http://www.openjs.com/scripts/jx/
jx={getHTTPObject:function(){var http=false;if(typeof ActiveXObject!='undefined'){try{http=new ActiveXObject("Msxml2.XMLHTTP");}
catch(e){try{http=new ActiveXObject("Microsoft.XMLHTTP");}
catch(E){http=false;}}}else if(window.XMLHttpRequest){try{http=new XMLHttpRequest();}
catch(e){http=false;}}
return http;},bind:function(user_options){var opt={'url':'','onSuccess':false,'onError':false,'format':"text",'method':"GET",'update':"",'loading':"",'loadingIndicator':""}
for(var key in opt){if(user_options[key]){opt[key]=user_options[key];}}
if(!opt.url)return;var div=false;if(opt.loadingIndicator){div=document.createElement("div");div.setAttribute("style","position:absolute;top:0px;left:0px;");div.setAttribute("class","loading-indicator");div.innerHTML=opt.loadingIndicator;document.getElementsByTagName("body")[0].appendChild(div);this.opt.loadingIndicator=div;}
if(opt.loading)document.getElementById(opt.loading).style.display="block";this.load(opt.url,function(data){if(opt.onSuccess)opt.onSuccess(data);if(opt.update)document.getElementById(opt.update).innerHTML=data;if(div)document.getElementsByTagName("body")[0].removeChild(div);if(opt.loading)document.getElementById(opt.loading).style.display="none";},opt.format,opt.method,opt);},init:function(){return this.getHTTPObject();}}
