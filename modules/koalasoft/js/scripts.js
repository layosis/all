
/**
 * @file
 * Defines Javascript behaviors for the Knowledge module.
 **/

(function ($) {

    
    

    $(document).ready(function(){ 
    	
    	$( "input[id*='pagarLecturas']" ).click(function() {
            
    		$("#mando").val("pagarLecturas");
    		var name = this.id;
    		var values = name.split("_");
    		$("#medidor").val(values[2]);
            $("#mes").val(values[0]);
            
        });
    	/*
    	$("[id*=" + /\b[.?]/gi +toggleGroup+"]").click(function() {
             $("#mando").val("pagarLecturas");
             alert("holaaaa");
             
        });*/
    	
        $("#block-koalasoft-payments #edit-ci").keypress(function() {
            $("#block-koalasoft-payments #edit-medidorf").val('');
        });
        $("#block-koalasoft-payments #edit-medidorf").focusin(function() {
            $("#block-koalasoft-payments #edit-medidorf").val('med-');
        });
        $("#block-koalasoft-payments #edit-medidorf").keypress(function() {
            $("#block-koalasoft-payments #edit-ci").val('');
        });
        
        $("#edit-field-medidor-und-0-target-id").blur(function() {
            //Allow only backspace, delete and enter
            var text = this.value;
            var meter = text.substring(text.lastIndexOf("(")+1,text.lastIndexOf(")"));
            var oldLecture = "";
            
            lectures.forEach(function(entry){
               if(entry['meter'] == meter){
                   oldLecture = entry['lecture'];
               }
               if(!parseInt(oldLecture) > 0)oldLecture = 0;
               $("#edit-field-lectura-anterior-und-0-value").val(oldLecture);
            });
        });
        
        $("#edit-field-lectura-actual-und-0-value").change(function() {
            //Allow only backspace, delete and enter
            var currentLecture = parseInt(this.value);
            var oldLecture = parseInt($("#edit-field-lectura-anterior-und-0-value").val());
            var consumption = currentLecture - oldLecture;
            var limit = parseInt(rates['limite']);
            var baseAmount = parseFloat(rates['montoBase']);
            var extraUnit = parseInt(rates['unidadExtra']);
            var extraAmount = parseFloat(rates['montoExtra']);
            var amount = 0;
            
            if (consumption <= limit){
            	amount =  baseAmount;
            }else {
            	amount = baseAmount + (consumption-limit)/extraUnit*extraAmount;
            }
            
            $("#edit-field-monto-und-0-value").val(amount);
            
        });
        
        $("#pagarLecturas").click(function() {
            $("#mando").val("pagarLecturas");
            
        });
        $("#filtrar").click(function() {
            $("#mando").val("filtrar");
            
        });
        
        
    });
       
})(jQuery);



