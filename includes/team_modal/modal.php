    <!-- The Modal -->
    <div id="team_modal" class="modal">
        <!-- Modal content -->
        <div id="modal_content" class="modal-content col-lg-108 offset-lg-6 col-120">
        </div>
    </div>

    <style type="text/css">
        .modal {
        	z-index: 9999;
        }
    </style>

    <script type="text/javascript">
        function open_modal(team_name, competition) {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                   // Typical action to be performed when the document is ready:
                   console.log(xhttp.responseText);
                   document.getElementById("modal_content").innerHTML = xhttp.responseText;
                   document.getElementById("team_modal").style.display = "block";
                }
            };
            xhttp.open("POST", "modal_content.php", true);
            xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhttp.send("team_name=" + team_name + "&competition=" + competition);
        }

        function close_modal(modal_id) {
        	document.getElementById("team_modal").style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
        	var modals = document.getElementsByClassName("modal");
        	for (var i = modals.length - 1; i >= 0; i--) {
        		var modal = modals[i];
        		if (event.target == modal) {
                    modal.style.display = "none";
                }
        	}
        }

        function open_url(team_name) {
            window.open("index.php?tab=team&team_name=" + team_name);
        }
    </script>