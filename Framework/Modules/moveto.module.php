<?php
	function moveTo($href){
		echo " 
			<script>
				document.location.href = '$href';
			</script>
		";
	}