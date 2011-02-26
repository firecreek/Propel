
var CompanyPermissions = {

  load: function()
  {
    var self = this;
    var defaultGrant = 3;
    
    
    $('#CompanyPermissions div.submit').hide();
    
    
    //Permission add/delete
    $('#CompanyPermissions input:checkbox').bind('change',function(){
      var personId = $(this).closest('tr').attr('rel-person-id');;
      var post = {};
      
      if($(this).is(':checked'))
      {
        //Grant default
        post = {
          data: {
            Grant: {}
          }
        };
        post['data']['Grant'][personId] = defaultGrant;
        post['data']['Action'] = 'add';
      }
      else
      {
        //Remove
        post = {
          data: {
            Person: {}
          }
        };
        post['data']['Person'][personId] = 0;
        post['data']['Action'] = 'remove';
      }
      
      self.submit(personId,post);
    });
    
    
    //Grant set changes
    $('#CompanyPermissions input:radio').bind('change',function(){
      var personId = $(this).attr('rel-person');
      var grantId = $('#CompanyPermissions input[rel-group='+$(this).attr('rel-group')+']:radio:checked').val();
      
      var post = {
        data: {
          Grant: {}
        }
      };
      post['data']['Grant'][personId] = grantId;
      post['data']['Action'] = 'grant';
      
      self.submit(personId,post);
    });
    
  },
  
  
  submit: function(personId,data)
  {
    var formUrl = $('#CompanyPermissions form').attr('action');
    
    $('tr[rel-person-id='+personId+'] td.loading div').show();
    $('tr[rel-person-id='+personId+'] input').attr('disabled','true');
    
    data['personId'] = personId;
      
    $.ajax({
      type: 'POST',
      url: formUrl+'.js',
      dataType: 'script',
      cache: false,
      data: data,
      success: function(response)
      {
        $('tr[rel-person-id='+personId+'] input').removeAttr('disabled');
        $('tr[rel-person-id='+personId+'] td.loading div').hide();
      }
    });
  }
  
  
}


$(document).ready(function() {
  CompanyPermissions.load();
});

