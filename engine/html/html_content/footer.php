		<script type="text/javascript">
		    document.onreadystatechange = function () {
		        document.getElementById('mainContainerLoaded').style.visibility="hidden";
		        var state = document.readyState
		        if (state == 'interactive') {
		
		        } else if (state == 'complete') {
		            setTimeout(function(){
		                document.getElementById('interactive');
		                document.getElementById('overlayLoader').style.visibility="hidden";
		                document.getElementById('mainContainerLoaded').style.visibility="visible";
		            },100);
		        }
		    }
		</script>
	</body>
</html>