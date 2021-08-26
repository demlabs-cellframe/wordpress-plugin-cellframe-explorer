function TxInfoFromJSONToHTML(data, address_info_url){
    var hash = "<div class='data_container'><h3 class='name_data_container'>Hash</h3>"+data.hash+"</div>";
    var token_ticker = "<div class='data_container'><h3 class='name_data_container'>Token ticker</h3>"+data.token_ticker+"</div>";
    var ts_created = "<div class='data_container'><h3 class='name_data_container'>TS Created</h3>"+data.ts_created+"</div>";
    var tables = "";
    data.items.forEach(function(element){
        switch(element.TYPE){
            case 'TOKEN':
                tables += "<div class='data_container'><h3 class=\'name_data_container\'>Token</h3>"+
                    "<table class='tx_table_addons_info'><tbody class='CE_inf_tbody'>" +
                    "<tr class='CE_inf_row'><td class='CE_inf_col_1'>Ticker</td><td class='CE_inf_col_2'>"+element.ticker+"</td></tr>"+
                    "<tr class='CE_inf_row'><td class='CE_inf_col_1'>Emission hash</td><td class='CE_inf_col_2'>"+element.token_emission_hash+"</td></tr>"+
                    "<tr class='CE_inf_row'><td class='CE_inf_col_1'>Emission chain id</td><td class='CE_inf_col_2'>"+element.token_emission_chain_id+"</td></tr>"+
                    "</tbody></table></div>";
                break;
            case "IN":
                tables += "<div class='data_container'><h3 class=\'name_data_container\'>IN</h3>"+
                    "<table class='tx_table_addons_info'><tbody class='CE_inf_tbody'>"+
                    "<tr class='CE_inf_row'><td class='CE_inf_col_1'>tx prev hash</td><td class='CE_inf_col_2'>"+element.tx_prev_hash+"</td></tr>"+
                    "<tr class='CE_inf_row'><td class='CE_inf_col_1'>tx out prev idx</td><td class='CE_inf_col_2'>"+element.tx_out_prev_idx+"</td></tr>"+
                    "</tbody></table></div>";
                break;
            case "OUT":
                var address = element.address.replace(/\s+/g, '').trim();
                tables += "<div class='data_container'><h3 class=\'name_data_container\'>OUT</h3>"+
                    "<table class='tx_table_addons_info'><tbody class='CE_inf_tbody'>"+
                    "<tr class='CE_inf_row'><td class='CE_inf_col_1'>Value</td><td class='CE_inf_col_2'>"+element.value+"</td></tr>"+
                    "<tr class='CE_inf_row'><td class='CE_inf_col_1'>Address</td><td class='CE_inf_col_2'><a href='"+address_info_url+"?addr="+address+"'>"+element.address+"</a></td></tr>"+
                    "</tbody></table></div>";
                break;
            case "SIG":
                tables += "<div class='data_container'><h3 class=\'name_data_container\'>SIG</h3>"+
                    "<table class='tx_table_addons_info'><tbody class='CE_inf_tbody'>"+
                    "<tr class='CE_inf_row'><td class='CE_inf_col_1'>SIG size</td><td class='CE_inf_col_2'>"+element.size+"</td></tr></tbody></table>"+
                    "<br/>"+
                    "<div class='data_container'><h3 class='name_data_container'>Signature</h3>"+
                    "<table class='tx_table_addons_info'><tbody class='CE_inf_tbody'>"+
                        "<tr class='CE_inf_row'><td class='CE_inf_col_1'>type</td><td class='CE_inf_col_2'>"+element.Signature.type+"</td></tr>"+
                        "<tr class='CE_inf_row'><td class='CE_inf_col_1'>Public key hash</td><td class='CE_inf_col_2'>"+element.Signature.pubKey.hash+"</td></tr>"+
                        "<tr class='CE_inf_row'><td class='CE_inf_col_1'>Public key size</td><td class='CE_inf_col_2'>"+element.Signature.pubKey.size+"</td></tr>"+
                        "<tr class='CE_inf_row'><td class='CE_inf_col_1'>Size</td><td class='CE_inf_col_2'>"+element.Signature.size+"</td></tr>"+
                    "</tbody></table>"+
                    "</div>"+
                    "</tr>"+
                    "</tbody></table></div>";
                break;
        }
    });
    var ret = hash + ts_created + token_ticker + tables;
    return ret;
}
