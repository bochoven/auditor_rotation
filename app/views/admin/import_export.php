<?$this->view('partials/head')?>

    <div class="container">
      <div class="row">
          <h2 class="span12"><i class="icon-wrench"></i> Administration</h2>
      </div><!--/row-->

      <div class="row">
        <div class="span12">
          <div class="well clearfix hide inactive status">
            <a style="margin-left: 30px" href="<?=url('admin/set_status/active')?>" style="margin-left: 30px" class="btn btn-success btn-large pull-right">Start <i class="icon-ok-sign"></i></a>
        Survey redirection is currently inactive, press start to activate. This will reset all the counters from the previous run. After the redirection has been activated, you cannot upload a new excel document or change the values below.
          </div>
          <div class="well clearfix hide active status">
            <a style="margin-left: 30px" href="<?=url('admin/set_status/inactive')?>" class="btn btn-danger btn-large pull-right">Stop <i class="icon-remove-sign"></i></a>
            Survey redirection is currently active, press stop to de-activate.
          </div>
        </div>
      </div>


      <div class="row">

        <div class="span8">

          <?$sobj = new Setting()?>
          <form id="settingForm" method="post" action="<?=url('admin/save_settings')?>" class="well form-horizontal">
            <h3>Survey link</h3>
            <div class="control-group">
              <label class="control-label" for="url">Survey URL</label>
              <div class="controls">
                <input type="text" class="span5" id="url" name='url' placeholder="http://" value="<?=$sobj->get_prop('url')?>">
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="questionId">Question ID</label>
              <div class="controls">
                <input type="text" class="input-large" id="questionId" name='question' placeholder="x987097xx" value="<?=$sobj->get_prop('question')?>">
              </div>
            </div>
            <div class="form-actions hide" style="margin-bottom: 0; padding-bottom: 0">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <a href="" class="btn">Cancel</a>
            </div>

         </form>

         <form class="well form-horizontal">

            <h3>Test link</h3>

            <div class="control-group">
              <label class="control-label" for="questionId">Choose a version</label>
              <div class="controls">
                <?$vers_obj = new Version();?>
                <select id="versionSelect">
                  <?foreach ($vers_obj->retrieve_many('id>0 ORDER BY desc') as $version):?>
                  <option value="<?=$version->code?>"><?=$version->desc?></option>
                  <?endforeach?>
                </select>
              </div>
            </div>
            
            <div class="control-group">
              <label>Survey Link</label>
              <input type="text" class="span6" id="exampleUrl" value=''>
                <a class="btn" id="exampleLink" href="#" target="_blank">Test</a>
            </div>
          </form>

        </div>
        <div class="span4">

          <div class="well">
            <p><b>Download excel file</b></p>
            <a class="btn btn-primary" href="<?=WEB_FOLDER?>downloads/Auditor_rotation.xls"><i class="icon-download-alt"></i> Download</a>
          </div>

          <form id="uploadForm" class="well" action="<?=url('admin/submit')?>" enctype="multipart/form-data"  method="post" accept-charset="utf-8">

            <p><b>Upload excel file</b></p>
            <p><input type="file" name="file"></p>

            <p class="uploadbutton hide"><button class="btn btn-primary" type="submit"><i class="icon-upload-alt"></i> Upload</button></p>

          </form>


        </div>  

      </div>
       

      <hr>

<?$this->view('partials/foot')?>

  <script type="text/javascript">
    
    $('#url').keyup(function(event) {
      if (event.which == 13) {
         event.preventDefault();
       }
       update_link();
       show_actions();
    });
    $('#questionId').keyup(function(event) {
      if (event.which == 13) {
         event.preventDefault();
       }
       update_link();
       show_actions();
    });
    $('#url').change(function(event) {
       update_link();
       show_actions();
    });
    $('#questionId').change(function(event) {
       update_link();
       show_actions();
    });
    $('#versionSelect').change(function(event) {
       update_link();
    });

    // page load
    update_link();

    // Show save/cancel buttons
    function show_actions()
    {
      $('form#settingForm div.form-actions').show();
    }

    function update_link()
    {
      link = $('#url').val() + '&' + $('#questionId').val() + '=' + $('#versionSelect').val();
      $('#exampleUrl').val(link);
      $('#exampleLink').attr('href', link);
    }

    // Submit setting form via ajax
    $('form#settingForm').submit(function(e){
      e.preventDefault();
      $.post( $(this).attr('action'), $(this).serialize(),
      function( data ) {
        $('form#settingForm div.form-actions').hide();
        alert('Settings are saved');
      });
    });

    $('input[name=file]').change(function(e){
      $('p.uploadbutton').show();
    })

    $('form#uploadForm').submit(function(e)
    {
      $('button[type=submit]').addClass('btn-success').text('Uploading...');
    })
  </script>

    <script type="text/javascript">

    update_status();

    $('.status a').click(function(e){
      e.preventDefault();

      // Ask for confirmation
      var answer = confirm("Are you sure you want to change the status?")
      if (answer){
        $.get( $(this).attr('href'),
        function( data ) {
          update_status();
          }
        );
      }
      
    });

    function update_status()
    {
      $.get( '<?=url('admin/xhttp_get_status')?>',
          function( data ) {
            $('.status').hide().filter('.' + data.status).show();
              if(data.status == 'active')
              {
                $('input').attr('disabled', 'disabled');
              }
              else
              {
                $('input').removeAttr('disabled');
              }
            },
        'json'
        );
    }
  </script>


  </body>
</html>
