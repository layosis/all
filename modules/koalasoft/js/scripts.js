
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
        
        //To calculate change of price
        $("#edit-field-lectura-actual-und-0-value").change(function() {
            var currentLecture = parseInt(this.value);
            var oldLecture = parseInt($("#edit-field-lectura-anterior-und-0-value").val());
            var consumption = currentLecture - oldLecture;
            var amount = 0;
            var currentPrice = 0; var currentUnit = 0; var firstLimit = 0;
            
            for(var i=0; i<rates.length; i++) {
            	var limit = parseInt(rates[i]['limite']);
            	var baseAmount = parseFloat(rates[i]['montoBase']);
                var extraUnit = parseInt(rates[i]['unidadExtra']);
                var extraAmount = parseFloat(rates[i]['montoExtra']);
                
                if ( i == 0 ) {
                	firstLimit = limit;
                }
                if (consumption <= limit && i == 0) {
                    amount = baseAmount;
                    break;
                } else if(consumption > limit){
                    currentPrice = extraAmount;
                    currentUnit = extraUnit;
                }
            }
            
            if (amount == 0){
            	amount = baseAmount + (consumption-firstLimit)/currentUnit*currentPrice;            	
            }
            
            $("#edit-field-monto-und-0-value").val(amount);
            $("#edit-field-monto-excedente-und-0-value").val(amount-baseAmount);
            
        });
        
        $("#pagarLecturas").click(function() {
            $("#mando").val("pagarLecturas");
            
        });
        $("#filtrar").click(function() {
            $("#mando").val("filtrar");
            
        });
        
        
    });
       
})(jQuery);



