<?$this->view('partials/head')?>

    <div class="container">
      <div class="row">
          <h2 class="span12"><i class="icon-dashboard"></i> Dashboard</h2>
      </div><!--/row-->



<table class='table table-striped'>
	<tr>
		<th>Code</th>
		<th>Description</th>
		<th>Threshold</th>
		<th>Started</th>
		<th>Completed</th>
	</tr>
	<?$vers_obj = new Version();?>
	<?foreach ($vers_obj->retrieve_many('id>0 ORDER BY desc') as $version):?>
	<tr>
		<td><?=$version->code?></td>
		<td><?=$version->desc?></td>
		<td><?=$version->threshold?></td>
		<td><?=$version->started?></td>
		<td><?=$version->completed?></td>
	</tr>
	<?endforeach?>
</table>


