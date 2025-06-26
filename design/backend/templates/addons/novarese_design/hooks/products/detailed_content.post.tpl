{if $wedding_halls}
    {include file="common/subheader.tpl" title=__("novarese_design.congratulatory_flower_settings") target="#congratulatory_flower_fields"}


    <div id="congratulatory_flower_fields" class="in collapse">
        <fieldset>
            <div class="control-group">
                <label for="congratulatory_flower" class="control-label">{__("novarese_design.is_congratulatory_flower")}:</label>
                <div class="controls">
                    <input type="hidden" name="product_data[congratulatory_flower]" value="N">
                    <input type="hidden" name="product_data[congratulatory_flower_hall]" value="">
                    
                    <input type="checkbox" id="congratulatory_flower" name="product_data[congratulatory_flower]" value="Y" {if $product_data.congratulatory_flower == "Y"}checked="checked"{/if}>
                </div>
            </div>

            {* $wedding_hallsをセレクトボックスで表示 *}
            <div id="congratulatory_flower_hall" class="control-group{if $product_data.congratulatory_flower != "Y"} hidden{/if}">
                <label for="congratulatory_flower_hall" class="control-label cm-required">{__("novarese_design.delivery_hall")}:</label>
                <div class="controls">
                    <select id="congratulatory_flower_hall" name="product_data[congratulatory_flower_hall]">
                        <option value="">{__("select")}</option>
                        {foreach $wedding_halls as $hall}
                            <option value="{$hall.hall_id}" {if $product_data.congratulatory_flower_hall == $hall.hall_id}selected{/if}>{$hall.hall}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
        </fieldset>
    </div>

    <script>
    //id="congratulatory_flower"がチェックされていたらid="congratulatory_flower_hall"を表示する
    (function (_, $) {
        $(document).ready(function () {
            $('#congratulatory_flower').on('change', function () {
                if ($(this).is(':checked')) {
                    $('#congratulatory_flower_hall').show();
                } else {
                    $('#congratulatory_flower_hall').hide();
                }
            });
        });
    })(Tygh, Tygh.$);
    </script>


    {* チケット商品フラグ *} 
    <div id="is_ticket" class="control-group">
        <label for="is_ticket" class="control-label">{__("novarese_design.is_ticket")}:</label>
        <div class="controls">
            <input type="hidden" name="product_data[is_ticket]" value="N">
            <input type="checkbox" id="is_ticket" name="product_data[is_ticket]" value="Y" {if $product_data.is_ticket == "Y"}checked="checked"{/if}>
        </div>
    </div>
{/if}
