<?$this->view('partials/head')?>

    <div class="container">
      <div class="row">
          <h2 class="span12"><i class="icon-group"></i> Participants</h2>
      </div><!--/row-->



<table class='table table-striped'>
	<tr>
		<th>ID</th>
		<th>Code</th>
		<th>Send date</th>
		<th>Return date</th>
		<th>IP Address</th>
	</tr>
	<?$part_obj = new Participant();?>
	<?foreach ($part_obj->retrieve_many('id>0 ORDER BY send_date') as $participant):?>
	<tr>
		<td><?=$participant->id?></td>
		<td><?=$participant->code?></td>
		<td><?=$participant->send_date?></td>
		<td><?=$participant->return_date?></td>
		<td><?=$participant->ip?></td>
	</tr>
	<?endforeach?>
</table>


