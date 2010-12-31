function basename (path, suffix) {
    // Returns the filename component of the path  
    // 
    // version: 1009.2513
    // discuss at: http://phpjs.org/functions/basename
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +   improved by: Ash Searle (http://hexmen.com/blog/)
    // +   improved by: Lincoln Ramsay
    // +   improved by: djmix
    // *     example 1: basename('/www/site/home.htm', '.htm');
    // *     returns 1: 'home'
    // *     example 2: basename('ecra.php?p=1');
    // *     returns 2: 'ecra.php?p=1'
    var b = path.replace(/^.*[\/\\]/g, '');
    
    if (typeof(suffix) == 'string' && b.substr(b.length-suffix.length) == suffix) {
        b = b.substr(0, b.length-suffix.length);
    }
    
    return b;
}
function dirname (path) {
    // Returns the directory name component of the path  
    // 
    // version: 1009.2513
    // discuss at: http://phpjs.org/functions/dirname
    // +   original by: Ozh
    // +   improved by: XoraX (http://www.xorax.info)
    // *     example 1: dirname('/etc/passwd');
    // *     returns 1: '/etc'
    // *     example 2: dirname('c:/Temp/x');
    // *     returns 2: 'c:/Temp'
    // *     example 3: dirname('/dir/test/');
    // *     returns 3: '/dir'
    
    return path.replace(/\\/g,'/').replace(/\/[^\/]*\/?$/, '');
}
function pathinfo (path, options) {
    // Returns information about a certain string  
    // 
    // version: 1009.2513
    // discuss at: http://phpjs.org/functions/pathinfo
    // +   original by: Nate
    // +    revised by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    improved by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: Inspired by actual PHP source: php5-5.2.6/ext/standard/string.c line #1559
    // %        note 1: The way the bitwise arguments are handled allows for greater flexibility
    // %        note 1: & compatability. We might even standardize this code and use a similar approach for
    // %        note 1: other bitwise PHP functions
    // %        note 2: php.js tries very hard to stay away from a core.js file with global dependencies, because we like
    // %        note 2: that you can just take a couple of functions and be on your way.
    // %        note 2: But by way we implemented this function, if you want you can still declare the PATHINFO_*
    // %        note 2: yourself, and then you can use: pathinfo('/www/index.html', PATHINFO_BASENAME | PATHINFO_EXTENSION);
    // %        note 2: which makes it fully compliant with PHP syntax.
    // -    depends on: dirname
    // -    depends on: basename
    // *     example 1: pathinfo('/www/htdocs/index.html', 1);
    // *     returns 1: '/www/htdocs'
    // *     example 2: pathinfo('/www/htdocs/index.html', 'PATHINFO_BASENAME');
    // *     returns 2: 'index.html'
    // *     example 3: pathinfo('/www/htdocs/index.html', 'PATHINFO_EXTENSION');
    // *     returns 3: 'html'
    // *     example 4: pathinfo('/www/htdocs/index.html', 'PATHINFO_FILENAME');
    // *     returns 4: 'index'
    // *     example 5: pathinfo('/www/htdocs/index.html', 2 | 4);
    // *     returns 5: {basename: 'index.html', extension: 'html'}
    // *     example 6: pathinfo('/www/htdocs/index.html', 'PATHINFO_ALL');
    // *     returns 6: {dirname: '/www/htdocs', basename: 'index.html', extension: 'html', filename: 'index'}
    // *     example 7: pathinfo('/www/htdocs/index.html');
    // *     returns 7: {dirname: '/www/htdocs', basename: 'index.html', extension: 'html', filename: 'index'}
    // Working vars
    var opt = '', optName = '', optTemp = 0, tmp_arr = {}, cnt = 0, i=0;
    var have_basename = false, have_extension = false, have_filename = false;
 
    // Input defaulting & sanitation
    if (!path) {return false;}
    if (!options) {options = 'PATHINFO_ALL';}
 
    // Initialize binary arguments. Both the string & integer (constant) input is
    // allowed
    var OPTS = {
        'PATHINFO_DIRNAME': 1,
        'PATHINFO_BASENAME': 2,
        'PATHINFO_EXTENSION': 4,
        'PATHINFO_FILENAME': 8,
        'PATHINFO_ALL': 0
    };
    // PATHINFO_ALL sums up all previously defined PATHINFOs (could just pre-calculate)
    for (optName in OPTS) {
        OPTS.PATHINFO_ALL = OPTS.PATHINFO_ALL | OPTS[optName];
    }
    if (typeof options !== 'number') { // Allow for a single string or an array of string flags
        options = [].concat(options);
        for (i=0; i < options.length; i++) {
            // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
            if (OPTS[options[i]]) {
                optTemp = optTemp | OPTS[options[i]];
            }
        }
        options = optTemp;
    }
 
    // Internal Functions
    var __getExt = function (path) {
        var str  = path+'';
        var dotP = str.lastIndexOf('.')+1;
        return str.substr(dotP);
    };
 
 
    // Gather path infos
    if (options & OPTS.PATHINFO_DIRNAME) {
        tmp_arr.dirname = this.dirname(path);
    }
 
    if (options & OPTS.PATHINFO_BASENAME) {
        if (false === have_basename) {
            have_basename = this.basename(path);
        }
        tmp_arr.basename = have_basename;
    }
 
    if (options & OPTS.PATHINFO_EXTENSION) {
        if (false === have_basename) {
            have_basename = this.basename(path);
        }
        if (false === have_extension) {
            have_extension = __getExt(have_basename);
        }
        tmp_arr.extension = have_extension;
    }
 
    if (options & OPTS.PATHINFO_FILENAME) {
        if (false === have_basename) {
            have_basename = this.basename(path);
        }
        if (false === have_extension) {
            have_extension = __getExt(have_basename);
        }
        if (false === have_filename) {
            have_filename  = have_basename.substr(0, (have_basename.length - have_extension.length)-1);
        }
 
        tmp_arr.filename = have_filename;
    }
 
 
    // If array contains only 1 element: return string
    cnt = 0;
    for (opt in tmp_arr){
        cnt++;
    }
    if (cnt == 1) {
        return tmp_arr[opt];
    }
 
    // Return full-blown array
    return tmp_arr;
}
Object.prototype.clone = function(){
	var copy = this.length ? [] : {};
	for(var i in this){
		if(typeof this[i] == 'object'){
			copy[i] = this[i].clone();
		}else{
			copy[i] = this[i];
		}
	}
	return copy;
};
Object.prototype.morph = function(def){
	var o = this.clone();
	for(var i in def){
		if(typeof o[i] == 'undefined'){
			o[i] = def[i];
		}else{
			if(typeof o[i] == typeof def[i] == 'object'){
				o[i] = o[i].inherit(def[i]);
			}
		}
	}
	return o;
};
bong._editor = {};
bong._editor.actives = [];
bong._editor.hideAll = function(){
	for(var i in this.actives){
		var dialog = bong._editor.actives[i];
		if(typeof dialog == 'object' && dialog.hide)
			dialog.hide();
	}
}
bong.editor = function(config){
	if(!config)config = {};
	var conf = config.morph({
		url: '',
		file: '',
		parsers: [],
		styles: [],
		title: '',
		save: function(){}
	});
	this.conf = conf;
	var handle = {};
	var editorDialog = bong.domify('<div class="bong-admin-editor">\
	<div class="bong-admin-editor-title">\
		Bong CodeMirror Editor '+(conf.title!='' ? conf.title : pathinfo(conf.file, 'PATHINFO_BASENAME').basename)+'\
	</div>\
	<div class="bong-admin-editor-body">\
		<div class="bong-admin-editor-filename">\
			<label>File: </label><span bong:handle="file">'+conf.file+'</span>\
		</div>\
		<textarea class="bong-admin-editor-code" bong:handle="code"></textarea>\
	</div>\
	<div class="bong-admin-editor-bottom">\
		<div class="bong-admin-editor-bottom-info" bong:handle="info">\
			Content Loaded\
		</div>\
		<button class="bong-dialog-btn bong-dialog-btn-default" bong:handle="save">Save</button>\
		<button class="bong-dialog-btn" bong:handle="cancel">Cancel</button>\
	</div>\
</div>', handle);
	var minimizeTab = bong.domify('<div class="bong-admin-editor-minimize-tab"> '
		+(conf.title!='' ? conf.title : pathinfo(conf.file, 'PATHINFO_BASENAME').basename)+
		'<div class="bong-admin-editor-minimize-tab-close"></div></div>');
	this.codemirror = null;
	bong._editor.hideAll();
	bong.href(conf.url).async(function(code){
		bong.byId('bong-admin-body').appendChild(editorDialog);
		bong.byId('bong-admin-editor-minimize-area').appendChild(minimizeTab);
		var CMConfig = {
			parserfile: [],
			stylesheet: [],
			lineNumbers: true,
			path: "/CodeMirror/js/",
			content: code,
			iframeClass: 'bong-admin-editor-body-textarea'
		};
		if(conf.embeddedPhpDoc){
			CMConfig.parserfile = ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "tokenizephp.js", "parsephp.js", "parsephphtmlmixed.js"];
			CMConfig.stylesheet = ["/CodeMirror/css/xmlcolors.css", "/CodeMirror/css/jscolors.css", "/CodeMirror/css/csscolors.css", "/CodeMirror/css/phpcolors.css"];
		}else if(conf.phpDoc){
			CMConfig.parserfile = ["tokenizephp.js", "parsephp.js"];
			CMConfig.stylesheet = ["/CodeMirror/css/phpcolors.css"];
		}
		this.codemirror = CodeMirror.fromTextArea(handle.code, CMConfig);
		var codemirror = this.codemirror;
		function resize(){
			editorDialog.style.height = bong.viewport.height()-200;
			editorDialog.style.width = bong.viewport.width()-180;
			jQuery(handle.code).next()[0].style.height = bong.viewport.height()-302;
		}
		resize();
		bong.addEvent(window, 'resize', resize);
		var _shown = true;
		var self = {
			hidden: function(){
				return !this.shown();
			},
			shown: function(){
				return _shown;
			},
			show: function(){
				bong._editor.hideAll();
				editorDialog.style.display = 'block';
				//jQuery(editorDialog).show('slow');
				_shown = true;
			},
			hide: function(){
				editorDialog.style.display = 'none';
				//jQuery(editorDialog).hide('slow');
				_shown = false;
			},
			toggle: function(){
				this.shown() ? this.hide() : this.show();
			},
			save: function(){
				conf.save(codemirror.getCode());
			},
			cancel: function(){
				this.hide();
				minimizeTab.style.display = 'none';
			}
		};
		bong.addEvent(minimizeTab, 'click', function(){
			self.toggle();
			if(self.shown())
				jQuery(this).addClass('bong-admin-editor-minimize-tab-selected');
			else
				jQuery(this).removeClass('bong-admin-editor-minimize-tab-selected');
		});
		bong.addEvent(handle.cancel, 'click', function(){
			self.cancel();
		});
		bong.addEvent(handle.save, 'click', function(){
			self.save();
		});
		bong._editor.actives.push(self);
		return self;
	});
}
/*
bong.editor({
    url: '<?php echo Resource::link() ?>/project/projectLayout',
    embeddedPhpDoc: true
});
*/
