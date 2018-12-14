<head>
    <title>System spersonalizowanego informowania użytkownika</title>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/screen.css">
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
	<?php 
	if(isset($_GET['drukuj'])){
	?> 
	<style>
	@media print {
		header, footer, aside, h2, #drukuj {
			display: none;
		}
	
		#container{
			background-color: white;
		}
		#print {
			width: 760px;
			margin: 0 auto;
		}
		#info{
			font-size: 20px;
			width:80%;
			margin: 0 auto;
			display: block;
		}
		#print a img{

			width: 750px;
			height: 750px;
		}
	}
	</style>
	<?php
	}
	?>
</head>