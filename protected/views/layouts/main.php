<!DOCTYPE html>
<html lang="en">
<head>
        <meta content="width=device-width; initial-scale=1.0; maximum-scale=1.0;" name="viewport">
        <meta content="yes" name="apple-mobile-web-app-capable">
       <!-- <link type="text/css" rel="stylesheet" href="css/mobile.css">-->
       <style type="text/css">
       .body {
		    top: 0;
		    left: 0;
		    right: 0;
		    bottom: 50px;
		    position: absolute;
		    overflow: hidden;
		}
		
		.scroll-y {
			overflow-y: auto;
			
		}
		.scroll-x, .scroll-y { -webkit-overflow-scrolling: touch; }
       </style>
</head>
<body>
   <div id="main" class="body scroll-y">
        
	</div>
   <div class="navbar navbar-fixed-bottom" style="position:absolute;bottom:0;height:60px;">
		<div class="navbar-inner" style="padding-top:10px;padding-left:1em;">
		   <form class="navbar-form" action="#" method="POST">				 
					<textarea id="message" style="margin-top: 15px" cols="150" rows="1"></textarea>
					<button type="button" id="send-btn" class="btn btn-primary">Invia</button>				
			</form>
		</div>
	</div>
</body>
</html>
<script type="text/html" id="user-message-template">
<div class="alert alert-success" style="margin:4px;margin-bottom:10px;">        	 
	<table class="">
		<tr>
			<td style="padding-right:1em;">
				%s:%s<br/>
				 <img src="images/balloon.png" /><br/>
				 
				 <strong>%s</strong>
			</td>
			<td>
	        	<p>
	        		%s
	        	</p>
			</td>
		</tr>
	</table>
</div>
</script>
<script type="text/html" id="incoming-message-template">
 <div class="alert alert-info">        	 
	<table class="">
		<tr>    				
			<td>    			
	        	<p>
	        		%s
	        	</p>
			</td>
			<td style="padding-left:1em;">
				%s:%s<br/>
				 <img src="images/balloon.png" /><br/>
				 <strong>%s %s</strong>
			</td>
		</tr>
	</table>
</div>
</script>
<?php 
Yii::app()->jii->addParam('user', array('firstname' => 'Cristian', 'lastname' => "Gabbanini"));

Yii::app()->jii->addParam('old_height', 0);

Yii::app()->jii->addFunction('sprintf', 'function (text){
    var i=1, args=arguments;
	    return text.replace(/%s/g, function(pattern){
	        return (i < args.length) ? args[i++] : "";
	    });
	}');

Yii::app()->jii->addFunction('fill_msg_template', 'function(text){
	var template = $("#user-message-template").text(),
		d = new Date(),
		hh = d.getHours(),
		mm = d.getMinutes();

	var html = jii.functions.sprintf(template,
		hh,
		mm,
		jii.params.user.firstname,
		//jii.params.user.lastname,
		text
	);
	
	$("#main").append(html);
}');

Yii::app()->jii->addFunction('clear_text_area', 'function(){
	$("#message").val("");
	$("#message").attr("rows", 1);
	$("#message").attr("style", "margin-top: 15px;");
}');
Yii::app()->jii->addFunction('post_message', 'function(){
		var message = $("#message").val();
		
		message = jii.functions.filter_message(message);

		if (message !== "") {
			jii.functions.fill_msg_template(message);
		}
		
}');

Yii::app()->jii->addFunction('filter_message', 'function(text){
	var RE = new RegExp("(\b[\w]{1,}\b){3}");
  	 text  = text.replace(RE,"$3<br/>");
	// text = text.replace(/^\w{3}/, "$1<br/>");
	text = text.replace(/\n/g, "<br/>");
	return text;
}');

Yii::app()->jii->addBindings('function(){
	$("#send-btn").bind("click", function(event){
		jii.functions.post_message();
		jii.functions.clear_text_area();
	});

	 
	$("#message").bind("keypress", function(event){
		if (event.keyCode === 13) {
			event.preventDefault();
			$(this).val($(this).val() + "\n");
			$("#message").autosize();
			//jii.functions.post_message();
			//jii.functions.clear_text_area();
		}
	 
	});
	$("#message").autosize();
}');

Yii::app()->jii->addParam('max_rows', 8);
Yii::app()->clientScript->registerScript('jii-main', Yii::app()->jii->getScript(), CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile('js/jquery.autosize-min.js',  CClientScript::POS_END)
?>