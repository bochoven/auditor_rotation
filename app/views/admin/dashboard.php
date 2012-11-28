<?$this->view('partials/head')?>

    <div class="container">
      <div class="row">
          <h2 class="span12"><i class="icon-dashboard"></i> Dashboard</h2>
      </div><!--/row-->

      <div class="row">
			<div class="span12">
				<?$s_obj = new Setting();$status=$s_obj->get_prop('status')?>
				<div class="alert <?=$status=='active'?'alert-success':''?>">
				
					<h3>
						Status: <?=$status?> |
						Started: <?=$s_obj->get_prop('started')?> |
						Stopped: <?=$s_obj->get_prop('stopped')?>
					</h3>
				</div>
    		</div>
      </div><!--/row-->

      <div class="row">
		<table class='table table-striped span12'>
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
	</div>

<?$this->view('partials/foot')?>

  </body>
</html>