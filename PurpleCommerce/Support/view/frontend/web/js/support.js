require(
    [
        'Magento_Ui/js/lib/validation/validator',
        'jquery',
        'jquery/jquery.cookie',
        'domReady!',
        'mage/translate'
], function(validator, $){

    if (typeof $.cookie('visit') === 'undefined'){
        $.cookie('visit', 'false');
    } else if ($.cookie('visit') === 'true') {
        $('.pc-support-btn').addClass('current');
    } else {
        $('.pc-support-btn').removeClass('current');
    }
    $(document).on('click','.pc-support-btn', function(){
        $('.pc-support-btn').addClass('current');
        $.cookie('visit', 'true');
    });
    
    $(document).on('click','.item', function(){
        $('.pc-support-btn').removeClass('current');
        $.cookie('visit', 'false');
    });
});
