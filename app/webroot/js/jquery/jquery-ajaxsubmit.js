
$.fn.ajaxSubmit = function(e) {

	this.submit(function(){
  
    var self = this;
    $(this).find('div.submit .saving').remove();
    $(this).find('div.submit').append('<div class="saving"></div>');
  
    var params = {};
    
    $(this)
      .find("input[checked], input[type='text'], input[type='hidden'], input[type='password'], input[type='submit'], select, textarea")
      .each(function() {
          params[ this.name || this.id || this.parentNode.name || this.parentNode.id ] = this.value;
      }
    );
    
    $.ajax({
      type: 'POST',
      url: this.getAttribute("action")+'.js',
      data: params,
      dataType: 'script',
      success: function(response)
      {
      }
    });

		return false;
	});
	
	return this;
}
