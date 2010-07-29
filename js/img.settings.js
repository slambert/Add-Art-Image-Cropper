//object to define the sizes that will be available.
var sizes = {
	"120x240":{"w":120,"h":240},
	"120x600":{"w":120,"h":600},
	"125x125":{"w":125,"h":125},
	"150x60":{"w":150,"h":60},
	"160x600":{"w":160,"h":600},
	"180x150":{"w":180,"h":150},
	"184x90":{"w":184,"h":90},
	"234x60":{"w":234,"h":60},
	"240x400":{"w":240,"h":400},
	"250x250":{"w":250,"h":250},
	"300x250":{"w":300,"h":250},
	"300x600":{"w":300,"h":600},
	"336x280":{"w":336,"h":280},
	"392x72":{"w":392,"h":72},
	"468x60":{"w":468,"h":60},
	"728x90":{"w":728,"h":90},		
	"88x31":{"w":88,"h":31}
};
// var to limit the size of the image preview.
var maxSize = 180;

// array with the name of the images, sizes and coordinates
var imgSettings = {};

// values of the last image edited, when change in the list will be assigned to the image
var currentImg = "";
var x1 = 0;
var y1 = 0;
var x2 = 0;
var y2 = 0;	
var width = 0;
var height = 0;
var resizew = 0;
var resizeh = 0;

// makes preview the img in next div to editable image.
function preview(img, selection) { 
	$('#save').removeAttr('disabled');
	var factorScale = 1;
	if(sizes[$('#choiceSize').val()]['w'] > sizes[$('#choiceSize').val()]['h'])
	{
		factorScale = maxSize / sizes[$('#choiceSize').val()]['w'];
	}
	else
	{
		factorScale = maxSize / sizes[$('#choiceSize').val()]['h'];
	}

	var scaleX = sizes[$('#choiceSize').val()]['w'] / selection["width"]; 
	var scaleY = sizes[$('#choiceSize').val()]['h'] / selection["height"];
	scaleX *= factorScale;
	scaleY *= factorScale;
	$('#editing + div > img').css({ 
		width: Math.round(scaleX * img['width']) + 'px', 
		height: Math.round(scaleY * img['height']) + 'px', 
		marginLeft: '-' + Math.round(scaleX * selection["x1"]) + 'px', 
		marginTop: '-' + Math.round(scaleY * selection["y1"]) + 'px' 
	}); 
} 

function setSizes(img, selection) {
	//alert(img['width']+"-"+img['height']);
	//alert(selection.width+"-"+selection.height+"-"+selection.x1+"-"+selection.y1+"-"+selection.x2+"-"+selection.y2);
	x1 = selection.x1;
	y1 = selection.y1;
	x2 = selection.x2;
	y2 = selection.y2;	
	width = selection.width;
	height = selection.height;
	resizeh = sizes[$('#choiceSize').val()]['h'];
	resizew = sizes[$('#choiceSize').val()]['w'];
}

// inserts an url image in the list and define its events.
function pushImg(name) {
	$("#choiceImage").append("<option value='"+img_path+"/"+name+"'>"+name+"</option>");
}
// when the document is ready to edit DOM.
$(document).ready(function () { 
	// factorscale takes into account the sizes of the list choice Size
	var factorScale = 1;
	if(sizes[$('#choiceSize').val()]['w'] > sizes[$('#choiceSize').val()]['h'])
		factorScale = maxSize / sizes[$('#choiceSize').val()]['w'];
	else
		factorScale = maxSize / sizes[$('#choiceSize').val()]['h'];
	
	// when changes the image of the list.
	$("#choiceImage").change(function () {
		// setting the entire image to edit.
		$("#editing").attr( {
			"src":$("#choiceImage").val(),
			"width":"400"
		});
		
		// setting the preview image.
		$("#preview").attr( {
			"src":$("#choiceImage").val(),
			"height":"400"
		});
		
		//alert($("#img-name").val());
		//alert($());
		// the name given to the image
		var name = $("#img-name").val();
		
		// edit the settings to export of the past image
		if(name != "")
			imgSettings["name"] = name;
		imgSettings["coord"] = {"x1":x1,"y1":y1,"x2":x2,"y2":y2};
		imgSettings["size"] = {"w":width,"h":height};
		imgSettings["resize"] = {"w":resizew,"h":resizeh};
		
		// put the name of the current picture
		$("#img-name").attr("value",imgSettings[$(this).val()]["name"]);
	});
	
	// makes the value of the list current as image
	$("#choiceImage").click(function () {
		currentImg = $(this).val();
	});
	
	// when changing choiceSize, changes the sizes of width and height of the preview and select.
	$("#choiceSize").change(function () {
		$('#editing').imgAreaSelect({ 
			hide: true,
			aspectRatio: sizes[this.value]['w'] + ':' + sizes[this.value]['h']
			//hide: true
		});
		
		$('#save').attr('disabled', 'disabled');
		
		var factorScale = 1;
		if(sizes[this.value]['w'] > sizes[this.value]['h'])
			factorScale = maxSize / sizes[this.value]['w'];
		else
			factorScale = maxSize / sizes[this.value]['h'];
		
		$('#previewContainer').css({ 
			width: (sizes[this.value]['w'] * factorScale) + 'px',
			height: (sizes[this.value]['h'] * factorScale) + 'px'
		}); 
	});
	
	$("#save").click(function() {
		// set the settings for the last image
		imgSettings["coord"] = {"x1":x1,"y1":y1,"x2":x2,"y2":y2};
		imgSettings["size"] = {"w":width,"h":height};
		imgSettings["resize"] = {"w":resizew,"h":resizeh};

		

		$('#x1').val(imgSettings["coord"].x1);
		$('#x2').val(imgSettings["coord"].x2);
		$('#y1').val(imgSettings["coord"].y1);
		$('#y2').val(imgSettings["coord"].y2);
		$('#width').val(imgSettings["size"].w);
		$('#height').val(imgSettings["size"].h);
		$('#newWidth').val(imgSettings["resize"].w);
		$('#newHeight').val(imgSettings["resize"].h);
		
		document.finished.submit();
	})
});
