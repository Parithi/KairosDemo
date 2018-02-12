<!DOCTYPE html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CompuSystems - Kairos API Demo</title>
	<meta name="description" content="Demo 1 for the tutorial: Creating Google Material Design Ripple Effects with SVG" />
	<meta name="keywords" content="svg, ripple effect, google material design, radial action, GreenSock, css, tutorial" />
	<meta name="author" content="Dennis Gaebel for Codrops" />
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" type="text/css" href="css/normalize.css" />
	<link rel="stylesheet" type="text/css" href="css/demo.css" />
	<link rel="stylesheet" type="text/css" href="css/component.css" />
	<link rel="stylesheet" type="text/css" href="css/buttons.css" />
	<!--[if IE]>
	  <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
	<div class="container">
		<header class="codrops-header">
		<h1>CompuSystems - Kairos API Demo<span>Enroll Subject to Kairos DB</span></h1>
			<nav class="codrops-demos">
				<a href="index.php">Verify</a>
				<a href="recognize.php">Recognize</a>
				<a class="current-demo" href="enroll.php">Enroll</a>
			</nav>
		</header>
		<div style="width:980px;margin:0 auto;text-align: center;">
		<div class="data">
				<input type="text" style="height:50px;border:0px;box-shadow:1px 1px 1px #afafaf;padding:16px;" placeholder="Enter subject_id" name="subject_id" id="subject_id"/>
				<br/>
				<div style="height:10px">
					<label id="subject_ids" style="font-size:11px;display:none;"></label>
				</div>
		</div>
		</div>
		<div class="content">
			<div id="buttons-div">
					<button id="webcam-btn" class="button button--nuka">Use Webcam</button>
					<button id="file-btn" class="button button--nuka">Use File</button>
					<button id="url-btn" class="button button--nuka">Use URL</button>
			</div>
			<div id="sources-div">
				<div id="webcam-div" style="display:none">
						<div id="webcam-info" style="position:relative;">
								<video id="v" style="position:absolute;top:0px;left:0px;" width="400" height="300"></video>
								<canvas id="c" style="display:none;position:absolute;top:0px;left:0px;"  width="400" height="300"></canvas>
								<button id="webcam-submit-btn" type="button" value="Take Picture" class="button button--nuka" style="position:absolute;top:0px;right:0px;">Take Picture</button>
						</div>
				</div>
				<div id="file-div" style="display:none">
						<div id="file-info" style="position:relative;">
								<input class="input__field input__field--hoshi" type="file" id="input-upload" style="position:absolute;top:0px;left:0px;" autocomplete="off">
								<canvas id="uploadedframe" style="display:none;position:absolute;top:100px;left:150px;"  width="400" height="300"></canvas>
								<div id="thumbnail"></div>
								<button id="input-submit-btn" type="button" class="button button--nuka" style="position:absolute;top:0px;right:0px;">Upload File</button>
						</div>
				</div>
				<div id="url-div" style="display:none">
						<div id="url-info" style="position:relative;">
								<input class="input__field input__field--hoshi" type="text" id="url" style="position:absolute;top:0px;left:0px;" placeholder="Enter URL" autocomplete="off">
								<button id="url-submit-btn" type="button" value="Send" class="button button--nuka" style="position:absolute;top:0px;right:0px;">Send</button>
						</div>
				</div>
			</div>
			<div id="results-div"  style="display:none">
					<h2 id="verifying">Enrolling..</h2>
					<h1 id="result-message" style="display:none"></h1>
					<button id="go-back-btn" type="button" class="button button--nuka" style="display:none;">Another one?</button>
			</div>
		</div>


	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
</body>
<script>

	var video = document.getElementById("v");
	var canvas = document.getElementById("c");
	var inputCanvas = document.getElementById("uploadedframe");
	ctx = inputCanvas.getContext("2d");
	var isWebCamClicked = false;
	var imageValid = false;

	loadSubjectIds();

	$("#webcam-btn").click(function(){
		showWebcam();
		runWebcam();
	});

	$("#file-btn").click(function(){
		showFile();
	});

	$("#url-btn").click(function(){
		showUrl();
	});

	$("#url-submit-btn").click(function(){
		if($("#url").val()!=''){
			var urlData = $("#url").val();
			authenticate(urlData);
			$("#url").val('');
		} else {
			alert("Enter URL");
		}
	});

	$("#go-back-btn").click(function(){
		reset();
	});

	$("#webcam-submit-btn").click(function(){
		webCamClicked();
	});

	document.getElementById("input-upload").addEventListener("change",function(e){
		var files = this.files;
		showThumbnail(files);
		$("#uploadedframe").show();
	},false);

	function showThumbnail(files){
		for(var i=0;i<files.length;i++){
			var file = files[i]
			var imageType = /image.*/
			if(!file.type.match(imageType)){
			console.log("Not an Image");
			continue;
			}

			var image = document.createElement("img");
			image.file = file;

			var reader = new FileReader()
			reader.onload = (function(aImg){
			return function(e){
				aImg.src = e.target.result;
			};
			}(image))

			var ret = reader.readAsDataURL(file);
			image.onload= function(){
			var ratioX = inputCanvas.width / image.naturalWidth;
			var ratioY = inputCanvas.height / image.naturalHeight;
			var ratio = Math.min(ratioX, ratioY);
			ctx.drawImage(image, 0, 0, image.naturalWidth * ratio, image.naturalHeight * ratio);
			imageValid = true;
			}
		}
	}

	$("#input-submit-btn").click(function(){
		if(imageValid){
			authenticate(inputCanvas.toDataURL());
		} else {
			alert ("Please select an image");
		}
	});
	

	function reset(){
		$("#buttons-div").fadeIn();
		$("#go-back-btn").hide();
		$("#verifying").show();
		$("#file-div").hide();
		$("#url-div").hide();
		$("#sources-div").show();
		$("#results-div").hide();
		$("#result-message").hide();
		$("#webcam-div").hide();
		$("#c").hide();
		$("#v").show();
		$("#uploadedframe").hide();
		$("#input-upload").val('');
		ctx.clearRect(0, 0, inputCanvas.width, inputCanvas.height);
		imageValid = false;
	}

	function showWebcam(){
		$("#buttons-div").fadeOut();
		$("#webcam-div").fadeIn();
	}

	function showFile(){
		$("#buttons-div").fadeOut();
		$("#file-div").fadeIn();
	}

	function showUrl(){
		$("#buttons-div").fadeOut();
		$("#url-div").fadeIn();
	}

	function runWebcam(){
		navigator.getUserMedia({video: true}, function(stream) {
			video.src = window.URL.createObjectURL(stream);
			video.play();
		}, function(err) { alert("there was an error " + err)});
	}

	function webCamClicked(){
		if(isWebCamClicked){
			var img = canvas.toDataURL("image/png");
			authenticate(img);
			isWebCamClicked = false;
		} else {
			canvas.getContext("2d").drawImage(video, 0, 0, 400, 300);
			$("#c").fadeIn();
			$("#v").fadeOut();
			$("#webcam-submit-btn").html("Authenticate");
			isWebCamClicked = true;
		}
	}

	function authenticate(data){
		if($("#subject_id").val() != ''){
		$("#sources-div").fadeOut();
		$("#results-div").fadeIn();
			$.post('kairos.php', { imageData: data, method : "enroll", subject_id : $("#subject_id").val()}, 
			function(returnedData){
				var resultData = JSON.parse(returnedData);
				$("#verifying").fadeOut();
				setTimeout(() => {
					$("#result-message").fadeIn();
					console.log(resultData);
					if(resultData && resultData.images && resultData.images.length > 0){
						if(resultData.images[0].transaction.status == "success" && resultData.images[0].transaction.confidence > 0.65 && resultData.images[0].transaction.subject_id == $("#subject_id").val()){
							$("#result-message").html('Enrolled \"'+$("#subject_id").val()+'\" Succesfully!');
							loadSubjectIds();
						} else {
							$("#result-message").html('Enrolling failed. Please check parameters.');
						}
					} else {
						$("#result-message").html('Enrolling failed. Please check parameters.');
					}
					setTimeout(() => {
							$("#go-back-btn").fadeIn();
							$("#go-back-btn").css("display","unset");
							$("#go-back-btn").css("float","inherit");
						}, 2000);
				}, 500);
			}).fail(function(){
				alert("error");
			});
		} else {
			alert("Enter subject_id");
		}
	}

	function loadSubjectIds(){
		$.post('kairos.php', {method : "load"}, 
			function(returnedData){
				var resultData = JSON.parse(returnedData);
				console.log(resultData);
				var subjectIdText = "Eg. ";
				for(subjectId in resultData['subject_ids']){
					subjectIdText += resultData['subject_ids'][subjectId] + ",";
				}
				$("#subject_ids").html(removeLastComma(subjectIdText));
				setTimeout(() => {
					$("#subject_ids").fadeIn();
				}, 200);
			}).fail(function(){
				console.log("unable to load subject ids");
			});
	}

	function removeLastComma(str) {
		return str.replace(/,(\s+)?$/, '');   
	}
		
</script>
</html>
