      <footer>
        <p>&copy; VU Amsterdam</p>
      </footer>

    </div><!--/.fluid-container-->

    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script src="//ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.2/jquery.dataTables.js"></script>
    <script src="<?=WEB_FOLDER?>assets/js/bootstrap.js"></script>
	<script src="<?=WEB_FOLDER?>assets/js/datatables-bootstrap.js"></script>
	<script src="<?=WEB_FOLDER?>assets/js/json2.js"></script>

	
	<script type="text/javascript">
		$(document).ready(function() {
		    $('.table').dataTable({
			            "sDom": "<'row'<'span6'l><'span6'f>r>t<'row'<'span6'i><'span6'p>>",
			            "iDisplayLength": 10,
			            "aLengthMenu": [[10, 20, -1], [10, 20, "All"]],
			            "sPaginationType": "bootstrap",
			            "bStateSave": true,
			            "aaSorting": [[1,'asc']]
			        });
		} );

	</script>
	<script type="text/javascript" charset="utf-8">
	$('body').on('hidden', '.modal', function () {
	  $(this).removeData('modal');
	});	</script>	