$.fn.dataTableExt.oApi.fnSetFilteringDelay = function(oSettings, iDelay){
   var _that = this;
   this.each(function(i){
       $.fn.dataTableExt.iApiIndex = i;
       iDelay = (iDelay && (/^[0-9]+$/.test(iDelay))) ? iDelay : 5000;

       var $this = this, oTimerId;
       var anControl = $('input', _that.fnSettings().aanFeatures.f);

       anControl.unbind('keyup').bind('keyup', function(){
           var $$this = $this;

           if (sPreviousSearch === null || sPreviousSearch != anControl.val()) {
               window.clearTimeout(oTimerId);

               oTimerId = window.setTimeout(function(){
                   $.fn.dataTableExt.iApiIndex = i;
                   _that.fnFilter(anControl.val());
               }, iDelay);
           }
       });

       return this;
   });
   return this;
}