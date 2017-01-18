<div id="currierPhoneBox" style="display:none">

<fieldset>

    <label for="currierPhone">{l s='*Required'  mod='currierphonemickey'}</label>
    <input id="currierPhone" type="text" placeholder="{l s='Your phone number' mod='currierphonemickey'}">
    <input type="submit" id="currierPhoneButton" value="{l s='Submit'  mod='currierphonemickey'}">
    <img src="{$moduleDir}/img/ajax-loader.gif" title="{l s='Loading'  mod='currierphonemickey'}" alt="{l s='Loading'  mod='currierphonemickey'}" class="currier_loader" />

</fieldset>

</div>

<style>
    #currierPhoneBox .message {
        display: inline-block;
        margin-bottom: -10px;
    }
    #currierPhoneBox {
        padding:20px;
        margin-bottom:20px;
        border: 1px solid #d6d4d4;
    }
    #currierPhoneBox input{
        margin-left:10px;
    }
    #currierPhoneBox>fieldset * {
        padding:10px;
    }
    #currierPhoneBox label, #currierPhoneBox .message {
        color:red;
    }
</style>
<script type="text/javascript" src="{$module_dir}js/carriersListMickey.js"></script>
<script type="text/javascript">
    $.carriersListMickey.ajaxPath = "{$ajax_url}";
    $.carriersListMickey.wrongPhone = "{$wrong_phone}";
    $.carriersListMickey.noPhone = "{$no_phone}";
    $.carriersListMickey.init();
</script>
