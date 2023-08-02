(function ($, Drupal) {
  Drupal.behaviors.ethicscompliance = {
    attach: function (context, settings) {
	    
      // Ensure accessible text colors for neutral backgrounds on content section paragraph type
      $('.field__item > div').each(function() {
        if ($(this).is('.bg-pimile, .bg-pimile-light, .bg-pimile-medium-light')) {
          $(this).addClass('neutral-background');
        } else if($(this).is('.bg-navy')) {
          $(this).addClass('dark-background');
        } else if($(this).is('.bg-tech-gold, .bg-tech-light-gold')) {
          $(this).addClass('semidark-background');
        }
      });	    
    }
  }
})(jQuery, Drupal);
