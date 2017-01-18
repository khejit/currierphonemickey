$.carriersListMickey = {

    init : function(){
        $.carriersListMickey.bindEvents();

        setTimeout(function(){
            var selected = $('.delivery_option_radio ').find('input:checked');
            $.carriersListMickey.showOrHideForm(selected);
        }, 0)

    },

    bindEvents : function(){

        $('#currierPhoneButton').on('click', function(e){
            e.preventDefault();
            $.carriersListMickey.updatePhoneMickey();
        });

    },

    showOrHideForm: function(selected){
        var $loader = $('#currierPhoneBox').find(".currier_loader");
        $loader.hide();
        var paymentSelector = $('#HOOK_PAYMENT');

        var selected = typeof selected !== 'undefined' ?  selected : 1;
        if ( $.carriersListMickey.isDpd(selected) ) {
            $('#currierPhoneBox').show();
            $.carriersListMickey.checkCurrierPhone();
        } else {
            $.carriersListMickey.removeWarningMickey();
            paymentSelector.show();
            $('#currierPhoneBox').hide();
        }
    },

    removeWarningMickey: function(){
        var parentSelector = $('#opc_payment_methods-content').find(".warning-mickey");
        parentSelector.remove();
    },

    updatePhoneMickey: function(){
        var $input = $('input#currierPhone');
        var $loader = $('#currierPhoneBox').find(".currier_loader");
        var $fieldset = $('#currierPhoneBox').find('fieldset');
        var $message = $('#currierPhoneBox').find('.message');

        $message.remove();
        $.carriersListMickey.removeWarningMickey();

        if($.carriersListMickey.isValidPhoneNumber($input)){
            $loader.show();
            $.post($.carriersListMickey.ajaxPath, {currier_phone:$input.val()}, function(json){
                $loader.hide();
                if(json.result=="ok") {
                    $fieldset.append( "<p class='message'>"+json.message+"</p>" );
                } else {
                    $fieldset.append( "<p class='message'>"+json.message+"</p>" );
                }
            });
            $.carriersListMickey.checkCurrierPhone();
        } else {
            $fieldset.append( "<p class='message'>"+ $.carriersListMickey.wrongPhone +"</p>" );
            $.carriersListMickey.checkCurrierPhone();
        }
    },

    checkCurrierPhone: function(){
        var paymentSelector = $('#HOOK_PAYMENT');
        var $input = $('input#currierPhone');
        $.carriersListMickey.removeWarningMickey();

        if(!$.carriersListMickey.isValidPhoneNumber($input)){
            paymentSelector.parent().append("<p class='warning warning-mickey'>"+$.carriersListMickey.noPhone +"</p>");
            paymentSelector.hide();

        } else {
            paymentSelector.show();
        }

    },

    isValidPhoneNumber : function(obj) {
        var pattern = new RegExp(/^(\+[0-9]{2})?( )?([0-9 ]{7,8})/i);
        return pattern.test(obj.val());
    },

    isDpd : function(toCheck){

        var dpdTest = false;
        var carrierId = parseInt(toCheck.val());
        $.each({31:true, 69:true, 44:true},function(idx,val){
            if(idx==carrierId){ dpdTest=true; }
        });
        return dpdTest;
    }

};