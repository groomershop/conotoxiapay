require(['jquery', 'Magento_Ui/js/modal/alert', 'mage/translate', 'uiComponent', 'jquery/ui'], function ($, alert, $t) {
    window.generateKeys = function (endpoint) {
        const private_key_id = '[data-ui-id="textarea-groups-conotoxia-pay-groups-required-conotoxia-pay-settings-fields-private-key-value"]';
        const public_key_id = '[data-ui-id="adminhtml-system-config-field-readonlyfield-0-textarea-groups-conotoxia-pay-groups-required-conotoxia-pay-settings-fields-public-key-value"]';

        /* Remove previous success message if present */
        const successMessage = ".conotoxia-pay-generation-success-message"
        if ($(successMessage)) {
            $(successMessage).remove();
        }

        $(this).text($t("Generating...")).attr('disabled', true);

        const self = this;
        $.ajax({
            url: endpoint,
            method: 'GET',
            dataType: 'json'
        }).done(function (response) {
            $('<div class="message message-success conotoxia-pay-generation-success-message">' + $t("Remember to save configuration after generating keys.") + '</div>').insertAfter(self);
            $(private_key_id).val(response.private_key).change();
            $(public_key_id).val(response.public_key).change();
        }).always(function () {
            $(self).text($t("Generate new key")).attr('disabled', false);
        });
    }
});
