<?$this->view('partials/head')?>

    <div class="container">
      <div class="row">
          <h2 class="span12"><i class="icon-wrench"></i> Database administration</h2>
      </div><!--/row-->
      <div class="row">
        <form class="well span3" action="<?=url('admin/submit')?>" enctype="multipart/form-data"  method="post" accept-charset="utf-8">

          <p><b>Upload excel file</b></p>
          <p><input type="file" name="file"></p>

          <p class="uploadbutton hide"><button class="btn btn-primary" type="submit"><i class="icon-upload-alt"></i> Upload</button></p>

        </form>

        <div class="well span3">
          <p><b>Download excel file</b></p>
          <a class="btn btn-primary" href="<?=WEB_FOLDER?>downloads/Auditor_rotation.xls"><i class="icon-download-alt"></i> Download</a>
        </div>

      </div>
       

      <hr>

<?$this->view('partials/foot')?>

  <script type="text/javascript">
    $('input[name=file]').change(function(e){
      $('p.uploadbutton').show();
    })

    $('form').submit(function(e)
    {
      $('button[type=submit]').addClass('btn-success').text('Uploading...');
    })
  </script>


  </body>
</html>
