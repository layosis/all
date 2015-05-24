/**
     * @file
     * Defines Javascript behaviors for the node module.
 */

  (function($) {
	$(document).ready(function() {
		 
    $("#asistencia #edit-buscar").click(function(){
    });

     $("#block-menu-menu-menu-administracion .menu-item-487 a").click(function() {
			 $('#header').css('display', 'none');
			 $('#menu-bar').css('display', 'none');
			 $('#footer').css('display', 'none');
			 $('#header').css('display', 'block');
			 $('#menu-bar').css('display', 'block');
			 $('#footer').css('display', 'block');

      window.print();                        


//       window.print();
			 return false;
		 })
		
	})
  })
 (jQuery);
