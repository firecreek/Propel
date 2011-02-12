
var SettingsAppearance = {
  
  active: '',
  originalColor: '',


  load: function()
  {
    var self = this;

    $('#colorpicker').ColorPicker({
      flat: true,
      onChange: function(hsb, hex) {
        SettingsAppearance.update(hex);
      }
    });
    
    
    $('#schemeExample a').bind('click',function(e){
      e.preventDefault();
    });
  
    $('.scheme-preview').bind('click',function(){
      self.active = $(this).attr('id').replace('SchemeStylePreview','');
      
      $('.scheme-preview').removeClass('active');
      $(this).addClass('active');
      
      var input = $('#SchemeStyle'+self.active);
      self.originalColor = input.val();
      
      $('#colorpicker').show();
      $('#colorpicker').ColorPickerSetColor(input.val());
      $('.colorpicker .colorpicker_undo').hide();
      
    
      $('#SchemeSchemeIdCustom').attr('checked','checked');
    });
    
    
    $('.colorpicker_close a').bind('click',function(e){
      e.preventDefault();
      $('#colorpicker').hide();
      $('.scheme-preview').removeClass('active');
    });
    
    
    $('.colorpicker_undo a').bind('click',function(e){
      e.preventDefault();
      $('#colorpicker').ColorPickerSetColor(self.originalColor);
      
      self.active = self.active;
      self.update(self.originalColor);
      
      $('.colorpicker .colorpicker_undo').hide();
    });
    
    
    $('.schemes input').bind('click',function(){
      var value = $(this).val();
      
      $('.scheme-preview').removeClass('active');
      $('#colorpicker').hide();
      
      if(colourSchemes[value])
      {
        for(var i in colourSchemes[value])
        {
          self.active = i;
          self.update(colourSchemes[value][i]);
        }
      }
    });
    
    
    var value = $('.schemes input[checked=checked]').val();
    if(colourSchemes[value])
    {
      for(var i in colourSchemes[value])
      {
        this.active = i;
        this.update(colourSchemes[value][i]);
      }
    }
    
  },
  
  
  update: function(hex)
  {
    $('.colorpicker .colorpicker_undo').show();
    
    if(hex.substring(0,1) !== '#') { hex = '#'+hex; }
  
    $('#SchemeStylePreview'+this.active+' span').css({
      backgroundColor:hex
    });
    $('#SchemeStyle'+this.active).val(hex);
    
    this.preview();
  },
  
  
  preview: function()
  {
    $('#schemeExample header').css({ backgroundColor: $('#SchemeStylebackgroundColor').val() });
    $('#schemeExample header h1').css({ color: $('#SchemeStyleprojectTextColour').val() });
    $('#schemeExample header h1 span').css({ color: $('#SchemeStyleclientTextColour').val() });
    $('#schemeExample a').css({ color: $('#SchemeStylelinkTextColour').val() });
    $('#schemeExample nav.tabs li a').css({ backgroundColor: $('#SchemeStyletabBackground').val() });
    $('#schemeExample nav.tabs li a').css({ color: $('#SchemeStyletabTextColour').val() });
    $('#schemeExample nav.tabs li.hover a').css({ backgroundColor: $('#SchemeStyletabBackgroundHover').val() });
    $('#schemeExample nav.tabs li.hover a').css({ color: $('#SchemeStyletabTextColourHover').val() });
    $('#schemeExample nav.tabs li.active a').css({ color: $('#SchemeStyletabTextColourActive').val() });
  }
  

}


$(document).ready(function() {
  SettingsAppearance.load();
});

