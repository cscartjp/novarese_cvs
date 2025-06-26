{include file="common/subheader.tpl" title=__("novarese_cvs.cvs_fixed_flag") target="#cvs_fixed_fields"}

<div id="cvs_fixed_fields" class="in collapse">
    <fieldset>
        <div class="control-group">
            <label for="cvs_fixed_flag" class="control-label">{__("novarese_cvs.cvs_fixed_flag")}:</label>
            <div class="controls">
                <input type="hidden" name="product_data[cvs_fixed]" value="N">
                <input type="checkbox" id="cvs_fixed_flag" name="product_data[cvs_fixed]" value="Y" {if $product_data.cvs_fixed == "Y"}checked="checked"{/if}>
                <span class="help-block">{__("novarese_cvs.cvs_fixed_flag_description")}</span>
            </div>
        </div>
    </fieldset>
</div>